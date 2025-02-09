<?php
declare(strict_types=1);

namespace App\Services;

use Exception;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;

/**
 * Created by PhpStorm.
 * Filename: DocumentService.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 21:55
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
final class OnlyOfficeService
{
	/**
	 * @throws Exception
	 */
	public static function commandRequest($method, $key, $meta = null): string
	{
		$arr = [
			"c"   => $method,
			"key" => (string) $key,
		];
		
		if ($meta) {
			$arr["meta"] = $meta;
		}
		
		$headerToken  = JWT::encode(["payload" => $arr], env('ONLYOFFICE_JWT_SECRET'), 'HS256');
		$arr["token"] = JWT::encode($arr, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
		$data         = json_encode($arr, JSON_THROW_ON_ERROR);
		
		$opts = [
			'http' => [
				'method'  => 'POST',
				'header'  => "Content-type: application/json\r\n".
					(empty($headerToken) ? "" : 'Authorization'.": Bearer $headerToken\r\n"),
				'content' => $data,
			],
		];
		
		$documentCommandUrl = 'http://'.config('office.local_url_office').'/command';
		$context            = stream_context_create($opts);
		$responseData       = file_get_contents($documentCommandUrl, false, $context);
		Log::info('commandRequest: '.$responseData);
		if ($responseData === false) {
			throw new \RuntimeException('Document Server connection error.');
		}
		$error = json_decode($responseData, true, 512, JSON_THROW_ON_ERROR)['error'];
		if ($error !== 0) {
			throw new \RuntimeException('Command Service Error #'.$error);
		}
		
		return $responseData;
	}
	
	public static function downloadFile($filePath): void
	{
		if (file_exists($filePath)) {
			if (ob_get_level()) {
				ob_end_clean();
			}
			
			// write headers to the response object
			@header('Content-Length: '.filesize($filePath));
			@header(
				'Content-Disposition: attachment; filename*=UTF-8\'\''.
				str_replace("+", "%20", urlencode(basename($filePath)))
			);
			@header('Content-Type: '.mime_content_type($filePath));
			@header('Access-Control-Allow-Origin: *');
			
			if ($fd = fopen($filePath, 'rb')) {
				while (!feof($fd)) {
					echo fread($fd, 1024);
				}
				fclose($fd);
			}
			exit;
		}
	}
	
	function processForceSave($data, $fileName, $userAddress)
	{
		$downloadUri = $data->url;
		if ($downloadUri === null) {
			$result["error"] = 1;
			
			return $result;
		}
		
		$curExt      = mb_strtolower('.'.pathinfo($fileName, PATHINFO_EXTENSION));  // get current file extension
		$downloadExt = mb_strtolower('.'.$data->filetype);                          // get the extension of the downloaded file
		
		$newFileName = false;
		
		// convert downloaded file to the file with the current extension if these extensions aren't equal
		if ($downloadExt != $curExt) {
			$key = generateRevisionId($downloadUri);
			
			try {
				sendlog("   Convert ".$downloadUri." from ".$downloadExt." to ".$curExt, "webedior-ajax.log");
				// convert file and give url to a new file
				$convertedData = getConvertedData($downloadUri, $downloadExt, $curExt, $key, false, $convertedUri);
				if (!empty($convertedUri)) {
					$downloadUri = $convertedUri;
				} else {
					sendlog("   Convert after save convertedUri is empty", "webedior-ajax.log");
					$baseNameWithoutExt = mb_substr($fileName, 0, mb_strlen($fileName) - mb_strlen($curExt));
					$newFileName        = true;
				}
			} catch (Exception $e) {
				sendlog("   Convert after save ".$e->getMessage(), "webedior-ajax.log");
				$newFileName = true;
			}
		}
		
		$saved = 1;
		
		if (!(($newData = file_get_contents(
				$downloadUri,
				false,
				stream_context_create(["http" => ["timeout" => 5]])
			)) === false)
		) {
			$baseNameWithoutExt = mb_substr($fileName, 0, mb_strlen($fileName) - mb_strlen($curExt));
			$isSubmitForm       = $data->forcesavetype == 3;  // SubmitForm
			
			if ($isSubmitForm) {
				if ($newFileName) {
					$fileName = GetCorrectName($baseNameWithoutExt.
						"-form".$downloadExt, $userAddress);  // get the correct file name if it already exists
				} else {
					$fileName = GetCorrectName($baseNameWithoutExt."-form".$curExt, $userAddress);
				}
				$forcesavePath = getStoragePath($fileName, $userAddress);
			} else {
				if ($newFileName) {
					$fileName = GetCorrectName($baseNameWithoutExt.$downloadExt, $userAddress);
				}
				// create forcesave path if it doesn't exist
				$forcesavePath = getForcesavePath($fileName, $userAddress, false);
				if ($forcesavePath == "") {
					$forcesavePath = getForcesavePath($fileName, $userAddress, true);
				}
			}
			
			file_put_contents($forcesavePath, $newData, LOCK_EX);
			
			if ($isSubmitForm) {
				$uid = $data->actions[0]->userid;                           // get the user id
				createMeta($fileName, $uid, "Filling Form", $userAddress);  // create meta data for the forcesaved file
				
				$formsDataUrl = $data->formsdataurl;
				if ($formsDataUrl) {
					$formsName = getCorrectName($baseNameWithoutExt.".txt", $userAddress);
					$formsPath = getStoragePath($formsName, $userAddress);
					
					if (!(($formsData = file_get_contents(
							$formsDataUrl,
							false,
							stream_context_create(["http" => ["timeout" => 5]])
						)) === false)
					) {
						file_put_contents($formsPath, $formsData, LOCK_EX);
					} else {
						throw new Exception("Document editing service didn't return formsData");
					}
				} else {
					throw new Exception('Document editing service did not return formsDataUrl');
				}
			}
			
			$saved = 0;
		}
		
		$result["error"] = $saved;
		
		return $result;
	}
	
	public static function generateRevisionId(string $expectedKey): string
	{
		if (mb_strlen($expectedKey) > 20) {
			$expectedKey = crc32($expectedKey);
		}  // if the expected key length is greater than 20, calculate the crc32 for it
		$key = preg_replace("[^0-9-.a-zA-Z_=]", "_", (string) $expectedKey);
		
		return mb_substr($key, 0, min([mb_strlen($key), 20]));
	}
	
	
}
