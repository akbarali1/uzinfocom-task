<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Akbarali\ViewModel\PaginationViewModel;
use App\ActionData\StoreDocumentActionData;
use App\ActionData\UploadFileActionData;
use App\Exceptions\DocumentException;
use App\Filters\DateRangeFilter;
use App\Filters\IdFilter;
use App\Filters\UserDocumentFilter;
use App\Filters\UserFilter;
use App\Services\DocumentService;
use App\Services\OnlyOfficeService;
use App\ViewModels\DocumentViewModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
	 * @return View
	 */
	public function create(): View
	{
		return view('document.create');
	}
	
	/**
	 * @throws DocumentException
	 */
	public function createFile(StoreDocumentActionData $actionData): RedirectResponse
	{
		$data = $this->service->createFile($actionData);
		
		//return DocumentViewModel::fromDataObject($data)->setCreateConfig()->toView('document.store');
		
		return to_route('document.edit', ['id' => $data->id]);
	}
	
	/**
	 * @throws DocumentException
	 */
	public function edit(int $id): View
	{
		$documentData = $this->service->getDocument($id, ['user']);
		
		return DocumentViewModel::fromDataObject($documentData)->setEditConfig()->toView('document.store');
	}
	
	/**
	 * @throws DocumentException
	 */
	public function view(int $id): View
	{
		$document = $this->service->getDocument($id, ['user']);
		
		return DocumentViewModel::fromDataObject($document)->setViewConfig()->toView('document.store');
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
