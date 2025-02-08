<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Akbarali\ActionData\ActionDataException;
use Akbarali\ViewModel\EmptyData;
use Akbarali\ViewModel\PaginationViewModel;
use App\ActionData\StoreDocumentActionData;
use App\ActionData\UploadFileActionData;
use App\Exceptions\DocumentException;
use App\Filters\UserDocumentFilter;
use App\Models\DocumentModel;
use App\Models\UserModel;
use App\Services\DocumentService;
use App\Services\OnlyOfficeService;
use App\ViewModels\DocumentViewModel;
use Firebase\JWT\JWT;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Created by PhpStorm.
 * Filename: DocumentController.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 21:55
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
final class DocumentController extends Controller
{
	public function __construct(
		protected DocumentService $service
	) {}
	
	/**
	 * @param  Request  $request
	 * @return View
	 */
	public function index(Request $request): View
	{
		$filters = collect();
		$filters->push(UserDocumentFilter::getFilterByUserRoles($request->user()));
		$dataCollection = $this->service->paginate((int) $request->get('page', 1), 25, $filters);
		
		return new PaginationViewModel($dataCollection, DocumentViewModel::class)->toView('document.index');
	}
	
	/**
	 * @return View
	 */
	public function create(Request $request): View
	{
		$user = $request->user();
		$type = empty($request->get("type")) ? "desktop" : $request->get("type");
		$file = public_path("/files/empty.docx");
		/** @var DocumentModel $document */
		$document = DocumentModel::query()->create([
			'user_id'        => $user->id,
			'title'          => 'new.docx',
			"file_path"      => '',
			"file_name"      => '',
			"file_size"      => filesize($file),
			"file_type"      => "docx",
			"last_edited_at" => now(),
		]);
		
		$path = storage_path('app/public/documents/word/user_'.$user->id.'/document_'.$document->id.'/');
		if (!file_exists($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
			throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
		}
		
		$fileName = $document->id.'_'.date('Y_m_d_H_i_s').'.docx';
		if (!copy($file, $path.$fileName)) {
			throw new \RuntimeException(sprintf('File "%s" was not created', $path));
		}
		
		$document->update([
			'file_path' => $path,
			'file_name' => $fileName,
		]);
		
		$directUrl         = str_replace(config('office.public_url'), config('office.local_url'), route('document.download', ['documentId' => $document->id]));
		$callbackUrl       = str_replace(config('office.public_url'), config('office.local_url'), route('document.callback', ['documentId' => $document->id]));
		$config            = [
			"type"         => $type,
			"documentType" => 'word',
			"document"     => [
				"title"         => $document->file_name,
				"url"           => $directUrl,
				"directUrl"     => "",
				"fileType"      => 'docx',
				"key"           => (string) $document->id,
				"info"          => [
					"owner"    => $user->name,
					"uploaded" => date('d.m.y'),
					"favorite" => true,
				],
				"permissions"   => [
					"comment"              => true,
					"copy"                 => true,
					"download"             => true,
					"edit"                 => true,
					"print"                => true,
					"fillForms"            => true,
					"modifyFilter"         => true,
					"modifyContentControl" => true,
					"review"               => false,
					"chat"                 => false,
					"reviewGroups"         => false,
					"commentGroups"        => false,
					"userInfoGroups"       => false,
					"protect"              => false,
				],
				"referenceData" => [
					"fileKey"    => json_encode([
						"fileName"    => $document->file_name,
						"userAddress" => $request->getClientIp(),
					], JSON_THROW_ON_ERROR),
					"instanceId" => 'http://localhost:8005',
				],
			],
			"editorConfig" => [
				"actionLink"    => !$request->has("actionLink") ? null : json_decode($request->get('actionLink')),
				"mode"          => 'edit',
				"lang"          => "en",
				"callbackUrl"   => $callbackUrl,
				"createUrl"     => url(),
				"coEditing"     => null,
				//				"createUrl"     => $user->id != "uid-0" ? $createUrl : null,
				//				"templates"     => $user->templates ? $templates : null,
				"user"          => [  // the user currently viewing or editing the document
					"id"    => 'u-'.$user->id,
					"name"  => $user->name,
					"group" => null,
					"image" => 'https://uzhackersw.uz/images/cat.jpg',
				],
				"embedded"      => [  // the parameters for the embedded document type
					"saveUrl"       => $directUrl,
					"embedUrl"      => $directUrl,
					"shareUrl"      => $directUrl,
					"toolbarDocked" => "top",  // the place for the embedded viewer toolbar (top or bottom)
				],
				"customization" => [                                                                                                                                                                                                                                                                                                                                                                                                                                                    // the parameters for the editor interface
					"about"      => true,
					// the About section display
					"comments"   => true,
					"feedback"   => false,  // the Feedback & Support menu button display
					// adds the request for the forced file saving to the callback handler when saving the document
					"forcesave"  => true,
					"submitForm" => true,  // if the Submit form button is displayed or not
					// settings for the Open file location menu button and upper right corner button
					"goback"     => route('document.index'),
				],
			],
		];
		$config["token"]   = JWT::encode($config, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
		$viewModel         = DocumentViewModel::fromDataObject(EmptyData::fromArray([]));
		$viewModel->config = $config;
		
		return $viewModel->toView('document.store');
	}
	
	/**
	 * @throws DocumentException
	 * @throws \JsonException
	 */
	public function edit(int $id, Request $request): View
	{
		/** @var UserModel $user */
		$user         = $request->user();
		$type         = empty($request->get("type")) ? "desktop" : $request->get("type");
		$documentData = $this->service->getDocument($id);
		$path         = $documentData->filePath.$documentData->fileName;
		if (!file_exists($path)) {
			throw DocumentException::fileNotFound();
		}
		
		$directUrl         = str_replace(config('office.public_url'), config('office.local_url'), route('document.download', ['documentId' => $documentData->id]));
		$callbackUrl       = str_replace(config('office.public_url'), config('office.local_url'), route('document.callback', ['documentId' => $documentData->id]));
		$config            = [
			"type"         => $type,
			"documentType" => 'word',
			"document"     => [
				"title"         => $documentData->fileName,
				"url"           => $directUrl,
				"directUrl"     => "",
				"fileType"      => 'docx',
				"key"           => (string) $documentData->id,
				"info"          => [
					"owner"    => $user->name,
					"uploaded" => date('d.m.y'),
					"favorite" => true,
				],
				"permissions"   => [
					"comment"              => true,
					"copy"                 => true,
					"download"             => true,
					"edit"                 => true,
					"print"                => true,
					"fillForms"            => true,
					"modifyFilter"         => true,
					"modifyContentControl" => true,
					"review"               => false,
					"chat"                 => false,
					"reviewGroups"         => false,
					"commentGroups"        => false,
					"userInfoGroups"       => false,
					"protect"              => false,
				],
				"referenceData" => [
					"fileKey"    => json_encode([
						"fileName"    => $documentData->fileName,
						"userAddress" => $request->getClientIp(),
					], JSON_THROW_ON_ERROR),
					"instanceId" => 'http://localhost:8005',
				],
			],
			"editorConfig" => [
				"actionLink"    => !$request->has("actionLink") ? null : json_decode($request->get('actionLink')),
				"mode"          => 'edit',
				"lang"          => "en",
				"callbackUrl"   => $callbackUrl,
				"createUrl"     => route('document.create'),
				"coEditing"     => null,
				"user"          => [  // the user currently viewing or editing the document
					"id"    => 'u-'.$user->id,
					"name"  => $user->name,
					"group" => null,
					"image" => 'https://uzhackersw.uz/images/cat.jpg',
				],
				"embedded"      => [  // the parameters for the embedded document type
					"saveUrl"       => $directUrl,
					"embedUrl"      => $directUrl,
					"shareUrl"      => $directUrl,
					"toolbarDocked" => "top",  // the place for the embedded viewer toolbar (top or bottom)
				],
				"customization" => [                                                                                                                                                                                                                                                                                                                                                                                                                                                    // the parameters for the editor interface
					"about"      => true,
					// the About section display
					"comments"   => true,
					"feedback"   => false,  // the Feedback & Support menu button display
					// adds the request for the forced file saving to the callback handler when saving the document
					"forcesave"  => true,
					"autosave"   => true,
					"submitForm" => true,  // if the Submit form button is displayed or not
					// settings for the Open file location menu button and upper right corner button
					"goback"     => [
						"blank" => false,
						"text"  => "Open file location",
						"url"   => route('document.index'),
					],
					"logo"       => [
						"image"      => "https://uzinfocom.uz/_next/static/media/uzinfocom-logo.8612a388.svg",
						"imageDark"  => "https://uzinfocom.uz/_next/static/media/uzinfocom-logo.8612a388.svg",
						"imageLight" => "https://uzinfocom.uz/_next/static/media/uzinfocom-logo.8612a388.svg",
						"text"       => "Open file location",
						"url"        => route('document.index'),
						"visible"    => true,
					],
					"customer"   => [
						"address"  => "My City, 123a-45",
						"info"     => "Some additional information",
						"logo"     => "https://example.com/logo-big.png",
						"logoDark" => "https://example.com/dark-logo-big.png",
						"mail"     => "john@example.com",
						"name"     => "John Smith and Co.",
						"phone"    => "123456789",
						"www"      => "example.com",
					],
				],
			
			],
		];
		$config["token"]   = JWT::encode($config, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
		$viewModel         = DocumentViewModel::fromDataObject(EmptyData::fromArray([]));
		$viewModel->config = $config;
		
		return $viewModel->toView('document.store');
	}
	
	/**
	 * @param  Request  $request
	 * @throws ActionDataException
	 * @throws ValidationException
	 * @return RedirectResponse
	 */
	public function store(Request $request): RedirectResponse
	{
		$request->request->set('user_id', $request->user()->id);
		$this->service->store(StoreDocumentActionData::fromRequest($request));
		
		return to_route('document.index')->with('message', trans('all.saved'));
	}
	
	/**
	 * @param  int      $id
	 * @param  Request  $request
	 * @throws ActionDataException
	 * @throws ValidationException
	 * @return RedirectResponse
	 */
	public function update(int $id, Request $request): RedirectResponse
	{
		$request->request->add([
			'id'      => $id,
			'user_id' => $request->user()->id,
		]);
		$this->service->store(StoreDocumentActionData::fromRequest($request));
		
		return to_route('user.index')->with('message', trans('all.updated'));
	}
	
	/**
	 * @param  int  $id
	 * @throws DocumentException
	 * @return RedirectResponse
	 */
	public function delete(int $id): RedirectResponse
	{
		$this->service->delete($id);
		
		return back()->with('message', trans('form.deleted'));
	}
	
	/**
	 * @throws DocumentException
	 * @throws \JsonException
	 */
	public function download(Request $request): ?JsonResponse
	{
		Log::info("download: ".json_encode($request->all(), JSON_THROW_ON_ERROR));
		
		$documentId = $request->get('key', $request->get('documentId'));
		if (empty($documentId)) {
			$response['status'] = 'success';
			
			$response['error'] = 'Document id not found';
			
			return response()->json($response);
		}
		$documentData = $this->service->getDocument((int) $documentId);
		
		$fullPath = $documentData->filePath.$documentData->fileName;
		if (!file_exists($fullPath)) {
			$response['status'] = 'success';
			$response['error']  = 'File not found';
			
			return response()->json($response);
		}
		
		OnlyOfficeService::downloadFile($fullPath);
	}
	
	/**
	 * @throws \JsonException
	 * @throws \Exception
	 */
	public function callback(Request $request): JsonResponse
	{
		Log::info("callback: ".json_encode($request->all()));
		
		$trackerStatus = [
			0 => 'NotFound',
			1 => 'Editing',
			2 => 'MustSave',
			3 => 'Corrupted',
			4 => 'Closed',
			6 => 'MustForceSave',
			7 => 'CorruptedForceSave',
		];
		$status        = $trackerStatus[$request->get('status')];  // get status from the request body
		switch ($status) {
			case "Editing":  // status == 1
				if (isset($request->get('actions')['0']['type']) && (int) $request->get('actions')['0']['type'] === 0) {   // finished edit
					//$user           = $request->get('actions')[0]['userid'];
					$commandRequest = OnlyOfficeService::commandRequest("forcesave", $request->get('key'));
					Log::info($commandRequest);
				}
				break;
			case "MustSave":  // status == 2
			case "Corrupted":  // status == 3
				//				$result = processSave($data, $fileName, $userAddress);
				//				break;
			case "MustForceSave":  // status == 6
				$url      = str_replace(config('office.public_url_office'), config('office.local_url_office'), $request->get('url'));
				$document = DocumentModel::query()->find($request->get('key'));
				if (is_null($document)) {
					$response['status'] = 'success';
					$response['error']  = 'Document not found';
					
					return response()->json($response);
				}
				$oldPath = $document->file_path.$document->file_name;
				$newPath = $document->file_path."history_".time()."_".$document->file_name;
				if (!rename($oldPath, $newPath)) {
					$response['status'] = 'success';
					$response['error']  = 'File rename error not found';
					
					return response()->json($response);
				}
				if (!copy($url, $oldPath)) {
					$response['status'] = 'success';
					$response['error']  = 'File copy error not found';
					
					return response()->json($response);
				}
				
				$document->update([
					"last_edited_at" => now(),
					"file_size"      => filesize($oldPath),
				]);
				break;
			case "CorruptedForceSave":  // status == 7
				//				$result = processForceSave($data, $fileName, $userAddress);
				//				break;
		}
		
		$response["error"]  = 0;
		$response['status'] = 'success';
		
		return response()->json($response);
	}
	
	/**
	 * @throws DocumentException
	 */
	public function view(int $id, Request $request): View
	{
		$document = $this->service->getDocument($id);
		$user     = $request->user();
		$path     = $document->filePath.$document->fileName;
		if (!file_exists($path)) {
			throw DocumentException::fileNotFound();
		}
		
		$directUrl         = str_replace(config('office.public_url_office'), config('office.local_url_office'), route('document.download', ['documentId' => $document->id]));
		$config            = [
			"type"         => 'docx',
			"documentType" => 'word',
			"document"     => [
				"title"       => $document->fileName,
				"url"         => $directUrl,
				"directUrl"   => "",
				"fileType"    => 'docx',
				"key"         => (string) $document->id,
				"info"        => [
					"owner"    => $user->name,
					"uploaded" => date('d.m.y'),
					"favorite" => true,
				],
				"permissions" => [
					"comment"      => false,
					"copy"         => true,
					"download"     => true,
					"edit"         => false,
					"chat"         => false,
					"print"        => true,
					"review"       => false,
					"modifyFilter" => true,
				],
			],
			"editorConfig" => [
				"actionLink"    => 'https://uzhackersw.uz',
				"mode"          => 'edit',
				"lang"          => "en",
				"coEditing"     => null,
				"user"          => [  // the user currently viewing or editing the document
					"id"    => 'u-'.$user->id,
					"name"  => $user->name,
					"group" => null,
					"image" => 'https://uzhackersw.uz/images/cat.jpg',
				],
				"customization" => [                                                                                                                                                                                                                                                                                                                                                                                                                                                    // the parameters for the editor interface
					"about"      => true,
					"comments"   => true,
					"feedback"   => false,  // the Feedback & Support menu button display
					// adds the request for the forced file saving to the callback handler when saving the document
					"forcesave"  => true,
					"autosave"   => true,
					"submitForm" => true,  // if the Submit form button is displayed or not
					// settings for the Open file location menu button and upper right corner button
					"goback"     => route('document.index'),
				],
			],
		];
		$config["token"]   = JWT::encode($config, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
		$viewModel         = DocumentViewModel::fromDataObject(EmptyData::fromArray([]));
		$viewModel->config = $config;
		
		return $viewModel->toView('document.store');
	}
	
	public function uploadFile(UploadFileActionData $actionData)
	{
		$this->service->uploadFile($actionData);
		
		return to_route('document.index')->with('message', trans('all.uploadFile'));
	}
	
	public function upload(Request $request)
	{
		return view('document.upload');
	}
	
	
}
