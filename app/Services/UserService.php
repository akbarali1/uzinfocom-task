<?php
declare(strict_types=1);

namespace App\Services;

use Akbarali\DataObject\DataObjectCollection;
use App\ActionData\StoreUserActionData;
use App\DataObjects\RoleDataObject;
use App\DataObjects\UserDataObject;
use App\Exceptions\UserException;
use App\Models\RoleModel;
use App\Models\UserModel;
use Illuminate\Validation\ValidationException;

/**
 * Created by PhpStorm.
 * Filename: UserService.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 17:30
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
final class UserService
{
	
	/**
	 * @param  int            $page
	 * @param  int            $limit
	 * @param  iterable|null  $filters
	 * @return DataObjectCollection<UserDataObject>
	 */
	public function paginate(int $page = 1, int $limit = 15, ?iterable $filters = null): DataObjectCollection
	{
		$model = UserModel::applyEloquentFilters($filters, ['roles'])->latest('users.id');
		$model->select(['users.*']);
		
		$totalCount = $model->count();
		$skip       = $limit * ($page - 1);
		$items      = $model->skip($skip)->take($limit)->get();
		$items->transform(fn(UserModel $value) => UserDataObject::fromModel($value));
		
		return new DataObjectCollection($items, $totalCount, $limit, $page);
	}
	
	/**
	 * @param  StoreUserActionData  $actionData
	 * @throws ValidationException
	 * @throws UserException
	 * @return UserDataObject
	 */
	public function store(StoreUserActionData $actionData): UserDataObject
	{
		$role = RoleModel::query()->find($actionData->role_id);
		if (is_null($role)) {
			throw UserException::roleNotFound();
		}
		if (isset($actionData->id)) {
			$model = UserModel::query()->find($actionData->id);
			$actionData->addValidationRule('id', 'required|exists:users,id');
		} else {
			$model = new UserModel();
			$actionData->addValidationRule('password', 'required');
		}
		$actionData->validateException();
		
		$model->fill($actionData->toArray(true));
		$model->save();
		
		$model->syncRoles($role);
		
		return UserDataObject::fromModel($model);
	}
	
	/**
	 * @param  int  $id
	 * @throws UserException
	 * @return UserDataObject
	 */
	public function getUser(int $id): UserDataObject
	{
		$model = UserModel::query()->with(['roles'])->find($id);
		if (is_null($model)) {
			throw UserException::userNotFound();
		}
		
		return UserDataObject::fromModel($model);
	}
	
	/**
	 * @param  int  $id
	 * @throws UserException
	 * @return void
	 */
	public function delete(int $id): void
	{
		$model = UserModel::query()->find($id);
		
		if (is_null($model)) {
			throw UserException::userNotFound();
		}
		
		if (UserModel::query()->count() === 1) {
			throw UserException::cannotDeleteLastUser();
		}
		
		$model->delete();
	}
	
	public function getRoles(iterable $filters = [], array|string $with = [])
	{
		return RoleModel::applyEloquentFilters($filters, $with)->get()->transform(fn($value) => RoleDataObject::fromModel($value));
	}
}
