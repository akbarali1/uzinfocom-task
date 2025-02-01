<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Akbarali\ActionData\ActionDataException;
use Akbarali\ViewModel\EmptyData;
use Akbarali\ViewModel\PaginationViewModel;
use App\ActionData\StoreUserActionData;
use App\Exceptions\UserException;
use App\Services\UserService;
use App\ViewModels\UserViewModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Created by PhpStorm.
 * Filename: UserController.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 17:29
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
final class UserController extends Controller
{
	public function __construct(
		protected UserService $service
	) {}
	
	/**
	 * @param  Request  $request
	 * @return View
	 */
	public function index(Request $request): View
	{
		$filters        = collect();
		$dataCollection = $this->service->paginate((int) $request->get('page', 1), 25, $filters);
		
		return new PaginationViewModel($dataCollection, UserViewModel::class)->toView('user.index');
	}
	
	/**
	 * @return View
	 */
	public function create(): View
	{
		$viewModel = UserViewModel::fromDataObject(EmptyData::fromArray([]));
		$roles     = $this->service->getRoles();
		$viewModel->setRolesList($roles);
		
		return $viewModel->toView('user.store');
	}
	
	/**
	 * @param  Request  $request
	 * @throws ActionDataException
	 * @throws UserException
	 * @throws ValidationException
	 * @return RedirectResponse
	 */
	public function store(Request $request): RedirectResponse
	{
		$request->request->set('user_id', $request->user()->id);
		$this->service->store(StoreUserActionData::fromRequest($request));
		
		return to_route('user.index')->with('message', trans('all.saved'));
	}
	
	/**
	 * @param  int  $id
	 * @throws UserException
	 * @return View|RedirectResponse
	 */
	public function edit(int $id): View|RedirectResponse
	{
		$userData  = $this->service->getUser($id);
		$viewModel = new UserViewModel($userData);
		
		$roles = $this->service->getRoles();
		$viewModel->setRolesList($roles);
		
		return $viewModel->toView('user.store');
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
		$this->service->store(StoreUserActionData::fromRequest($request));
		
		return to_route('user.index')->with('message', trans('all.updated'));
	}
	
	/**
	 * @param  int  $id
	 * @throws UserException
	 * @return RedirectResponse
	 */
	public function delete(int $id): RedirectResponse
	{
		$this->service->delete($id);
		
		return back()->with('message', trans('form.deleted'));
		//			return to_route('user.index')->withErrors($e->getMessage());
	}
}
