<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Akbarali\ActionData\ActionDataException;
use Akbarali\ViewModel\EmptyData;
use Akbarali\ViewModel\PaginationViewModel;
use App\ActionData\StoreDocumentActionData;
use App\Exceptions\DocumentException;
use App\Services\DocumentService;
use App\ViewModels\DocumentViewModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Firebase\JWT\JWT;

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
		$filters        = collect();
		$dataCollection = $this->service->paginate((int) $request->get('page', 1), 25, $filters);
		
		return new PaginationViewModel($dataCollection, DocumentViewModel::class)->toView('document.index');
	}
	
	/**
	 * @return View
	 */
	public function create(): View
	{
		$user = auth()->user(); 

		$payload = [
			"iat" => time(),
			"exp" => time() + 3600,
			"document" => [
				"fileType" => "docx",
				"key" => "Khirz6zTPdfd7",
				"title" => "example.docx",
				"url" => "https://uzinfocom.akbarali.uz/files/new.docx"
			],
			"editorConfig" => [
				"mode" => "edit",
				"callbackUrl" => "http://yourserver.com/callback",
				"user" => [
					"id" => $user->id,
					"name" => $user->name
				]
			],
		];
		
		$token = JWT::encode($payload, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
		$viewModel = DocumentViewModel::fromDataObject(EmptyData::fromArray([]));
		$viewModel->token = $token;

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
	 * @param  int  $id
	 * @throws DocumentException
	 * @return View|RedirectResponse
	 */
	public function edit(int $id): View|RedirectResponse
	{
		$userData  = $this->service->getDocument($id);
		$viewModel = DocumentViewModel::fromDataObject($userData);
		
		return $viewModel->toView('document.store');
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
}
