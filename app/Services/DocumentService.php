<?php
declare(strict_types=1);

namespace App\Services;

use Akbarali\DataObject\DataObjectCollection;
use App\ActionData\UploadFileActionData;
use App\DataObjects\DocumentDataObject;
use App\Exceptions\DocumentException;
use App\Models\DocumentHistoryModel;
use App\Models\DocumentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
final class DocumentService
{
	
	/**
	 * @param  int            $page
	 * @param  int            $limit
	 * @param  iterable|null  $filters
	 * @param  array          $with
	 * @return DataObjectCollection<DocumentDataObject>
	 */
	public function paginate(int $page = 1, int $limit = 15, ?iterable $filters = null, array $with = []): DataObjectCollection
	{
		$model = DocumentModel::applyEloquentFilters($filters, $with)->latest('documents.id');
		$model->select(['documents.*']);
		
		$totalCount = $model->count();
		$skip       = $limit * ($page - 1);
		$items      = $model->skip($skip)->take($limit)->get();
		$items->transform(fn(DocumentModel $value) => DocumentDataObject::fromModel($value));
		
		return new DataObjectCollection($items, $totalCount, $limit, $page);
	}
	
	/**
	 * @param  int    $id
	 * @param  array  $with
	 * @throws DocumentException
	 * @return DocumentDataObject
	 */
	public function getDocument(int $id, array $with = []): DocumentDataObject
	{
		/** @var DocumentModel $model */
		$model = DocumentModel::query()->with($with)->find($id);
		if (is_null($model)) {
			throw DocumentException::documentNotFound();
		}
		
		return DocumentDataObject::fromModel($model);
	}
	
	/**
	 * @param  string  $key
	 * @param  array   $with
	 * @throws DocumentException
	 * @return DocumentDataObject
	 */
	public function getDocumentByKey(string $key, array $with = []): DocumentDataObject
	{
		$model = DocumentModel::query()->with($with)->firstWhere('key', '=', $key);
		if (is_null($model)) {
			throw DocumentException::documentNotFound();
		}
		
		return DocumentDataObject::fromModel($model);
	}
	
	/**
	 * @param  int  $id
	 * @throws DocumentException
	 * @return void
	 */
	public function delete(int $id): void
	{
		$model = DocumentModel::query()->find($id);
		
		if (is_null($model)) {
			throw DocumentException::documentNotFound();
		}
		
		$model->delete();
	}
	
	/**
	 * @throws DocumentException
	 */
	public function uploadFile(UploadFileActionData $actionData): void
	{
		/** @var DocumentModel $document */
		$document = DocumentModel::query()->create([
			"user_id"   => $actionData->userId,
			"title"     => "File",
			"file_name" => $actionData->file->getClientOriginalName(),
			"file_type" => $actionData->file->getMimeType(),
			"file_path" => 'none',
			"file_size" => 0,
		]);
		$path     = storage_path('app/public/documents/word/user_'.$actionData->userId.'/document_'.$document->id.'/');
		if (!$actionData->file->move($path, $document->file_name)) {
			throw DocumentException::fileUploadFailed();
		}
		
		$document->update([
			'file_path'      => $path,
			"file_size"      => filesize($path.$document->file_name),
			"last_edited_at" => now(),
			"key"            => OnlyOfficeService::generateRevisionId($path.$document->file_name),
		]);
	}
	
	/**
	 * @throws DocumentException
	 * @throws \Exception
	 */
	public function rename(int $id, string $newName): array
	{
		/** @var DocumentModel $document */
		$document = DocumentModel::query()->find($id);
		
		if (is_null($document)) {
			throw DocumentException::documentNotFound();
		}
		
		$fullPath = $document->file_path.$document->file_name;
		if (!file_exists($fullPath)) {
			throw DocumentException::fileNotFound();
		}
		
		$newName         .= '.'.pathinfo($document->file_name, PATHINFO_EXTENSION);
		$newFileFullPath = $document->file_path.$newName;
		
		if (!rename($fullPath, $newFileFullPath)) {
			throw DocumentException::renameError();
		}
		
		$document->update([
			"file_name" => $newName,
		]);
		$commandRequest = OnlyOfficeService::commandRequest("meta", $document->id, ["title" => $document->file_name]);
		Log::info("CommandRequest rename: ".$commandRequest);
		
		return ["result" => json_decode($commandRequest, true, 512, JSON_THROW_ON_ERROR)];
	}
	
	public function saveCallbackFile(Request $request): array
	{
		$userId = (int) explode('-', $request->get('actions')[0]['userid'])[1];
		/** @var DocumentModel $document */
		$document = DocumentModel::query()->find($request->get('documentId'));
		if (is_null($document)) {
			$response['status'] = 'error';
			$response['error']  = 'Document not found';
			
			return $response;
		}
		$oldPath     = $document->file_path.$document->file_name;
		$newFileName = "history_"."_".$document->file_name;
		$newPath     = $document->file_path.$newFileName;
		if (!rename($oldPath, $newPath)) {
			$response['status'] = 'error';
			$response['error']  = 'File rename error not found';
			
			return $response;
		}
		$url = str_replace(config('office.public_url_office'), config('office.local_url_office'), $request->get('url'));
		if (!copy($url, $oldPath)) {
			$response['status'] = 'error';
			$response['error']  = 'File copy error not found';
			
			return $response;
		}
		
		DocumentHistoryModel::query()->create([
			'user_id'     => $userId,
			'document_id' => $document->id,
			'title'       => $document->title,
			'description' => $document->description,
			'file_name'   => $newFileName,
			'file_path'   => $document->file_path,
			'file_size'   => filesize($newPath),
			'file_type'   => $document->file_type,
			'action'      => 'update',
			'created_at'  => $document->created_at,
			'updated_at'  => $document->updated_at,
		]);
		
		$document->update([
			"last_edited_at" => now(),
			"file_size"      => filesize($oldPath),
		]);
		$response['status'] = 'success';
		$response['error']  = 0;
		
		return $response;
	}
	
	/**
	 * @throws DocumentException
	 */
	public function getHistory(int $id): array
	{
		$document = $this->getDocument($id, ['user']);
		
		/** @var DocumentHistoryModel[]|Collection $history */
		$history = DocumentHistoryModel::query()
			->where('document_id', $document->id)
			->oldest()
			->get();
		
		if ($history->isEmpty()) {
			return [
				[
					"currentVersion" => 1,
					"history"        => [
						"key"     => (string) $document->key,
						"created" => $document->createdAt->format('Y-m-d H:i:s'),
						"user"    => [
							"id"   => 'u-'.$document->userId,
							"name" => $document->user->name ?? "Unknown",
						],
					],
				],
				[
					"fileType" => pathinfo($document->fileName, PATHINFO_EXTENSION),
					"key"      => (string) $document->key,
					"url"      => route('document.download', ['documentId' => $document->id]),
					"version"  => 1,
				],
			];
		}
		
		$curVer   = $history->count();
		$hist     = [];
		$histData = [];
		
		foreach ($history as $key => $historyItem) {
			$version = $key + 1;
			$obj     = [
				"key"     => (string) $historyItem->id,
				"version" => $version,
				"created" => $historyItem->created_at->format('Y-m-d H:i:s'),
				"user"    => [
					"id"   => (string) $historyItem->user_id,
					"name" => $historyItem->user->name ?? "Unknown",
				],
			];
			if ($version > 1) {
				$prevHistory    = $history[$key - 1];
				$obj["changes"] = [
					[
						"created" => $historyItem->created_at->format('Y-m-d H:i:s'),
						"user"    => [
							"id"   => 'u-'.$historyItem->user_id,
							"name" => $historyItem->user->name ?? "Unknown",
						],
					],
				];
			}
			
			$fileExtension = pathinfo($historyItem->file_path, PATHINFO_EXTENSION);
			$fileUrl       = route('document.downloadHistory', ['documentId' => $document->id, 'version' => $version]);
			$dataObj       = [
				"fileType" => $fileExtension,
				"key"      => (string) $historyItem->id,
				"url"      => $fileUrl,
				"version"  => $version,
			];
			
			if ($version > 1) {
				$prevData            = $histData[$key - 1];
				$dataObj["previous"] = [
					"fileType" => $prevData["fileType"],
					"key"      => $prevData["key"],
					"url"      => $prevData["url"],
				];
			}
			
			$hist[]         = $obj;
			$histData[$key] = $dataObj;
		}
		
		return [
			[
				
				"currentVersion" => $curVer,
				"history"        => $hist,
			],
			[
				$histData,
			],
		];
	}
	
}
