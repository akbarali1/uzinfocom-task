<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Akbarali\ActionData\ActionDataException;
use Akbarali\ViewModel\PaginationViewModel;
use App\ActionData\StoreDocumentActionData;
use App\ActionData\UploadFileActionData;
use App\DataObjects\DocumentDataObject;
use App\Exceptions\DocumentException;
use App\Filters\DateRangeFilter;
use App\Filters\IdFilter;
use App\Filters\UserDocumentFilter;
use App\Filters\UserFilter;
use App\Models\DocumentModel;
use App\Services\DocumentService;
use App\Services\OnlyOfficeService;
use App\ViewModels\DocumentViewModel;
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
		protected DocumentService $service,
		protected OnlyOfficeService $onlyOfficeService
	) {}
	
	/**
	 * @param  Request  $request
	 * @return View
	 */
	public function index(Request $request): View
	{
		$filters = collect();
		$filters->push(UserDocumentFilter::getFilterByUserRoles($request->user()));
		$filters->push(UserFilter::getFilter());
		$filters->push(IdFilter::getFilter());
		$filters->push(DateRangeFilter::getDateRangeFilter());
		$dataCollection = $this->service->paginate((int) $request->get('page', 1), 20, $filters, ['user']);
		
		return new PaginationViewModel($dataCollection, DocumentViewModel::class)->toView('document.index');
	}
	
	/**
	 * @param  Request  $request
	 * @return View
	 */
	public function create(Request $request): View
	{
		$user = $request->user();
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
			"key"       => OnlyOfficeService::generateRevisionId($path.$fileName),
		]);
		
		return DocumentViewModel::fromDataObject(DocumentDataObject::fromModel($document))->setCreateConfig()->toView('document.store');
	}
	
	/**
	 * @throws DocumentException
	 */
	public function edit(int $id): View
	{
		$documentData = $this->service->getDocument($id);
		
		return DocumentViewModel::fromDataObject($documentData)->setEditConfig()->toView('document.store');
	}
	
	/**
	 * @throws DocumentException
	 */
	public function view(int $id): View
	{
		$document = $this->service->getDocument($id);
		
		return DocumentViewModel::fromDataObject($document)->setViewConfig()->toView('document.store');
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
		
		OnlyOfficeService::downloadFile($documentData->filePath.$documentData->fileName);
		
		return null;
	}
	
	/**
	 * @throws \JsonException
	 * @throws \Exception
	 */
	public function callback(Request $request): JsonResponse
	{
		Log::info("callback: ".json_encode($request->all()));
		if (is_null($request->get('status'))) {
			return response()->json([]);
		}
		
		$response = $this->onlyOfficeService->callback($request);
		
		return response()->json($response);
	}
	
	/**
	 * @throws DocumentException
	 */
	public function uploadFile(UploadFileActionData $actionData): RedirectResponse
	{
		$this->service->uploadFile($actionData);
		
		return to_route('document.index')->with('message', trans('all.uploadFile'));
	}
	
	public function upload()
	{
		return view('document.upload');
	}
	
	public function history(Request $request): void
	{
		Log::info("History: ".json_encode($request->all()));
	}
	
	/**
	 * @throws DocumentException
	 */
	public function historyObj(int $id, Request $request): JsonResponse
	{
		Log::info("History OBJ: ".json_encode($request->all()));
		
		$data = $this->service->getHistory($id);
		
		return response()->json($data);
	}
	
	/**
	 * @throws DocumentException
	 */
	public function rename(int $id, Request $request): JsonResponse
	{
		Log::info("Rename: ".json_encode($request->all()));
		
		$data = $this->service->rename($id, $request->get('newFileName'));
		
		return response()->json($data);
	}
	
}
