<?php
declare(strict_types=1);

namespace App\Services;

use Akbarali\DataObject\DataObjectCollection;
use App\ActionData\StoreUserActionData;
use App\DataObjects\UserDataObject;
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
		$model = UserModel::applyEloquentFilters($filters)->latest();
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
	 * @return UserDataObject
	 */
	public function store(StoreUserActionData $actionData): UserDataObject
	{
		if (isset($actionData->id)) {
			$subject = UserModel::query()->find($actionData->id);
			$actionData->addValidationRule('id', 'required|exists:subjects,id');
		} else {
			$subject = new UserModel();
		}
		$actionData->validateException();
		
		$subject->fill($actionData->toArray());
		$subject->save();
		
		return UserDataObject::fromModel($subject);
	}
	
	/**
	 * @param  int  $id
	 * @return UserDataObject
	 */
	public function getSubject(int $id): UserDataObject
	{
		$subject = UserModel::query()->find($id);
		if (is_null($subject)) {
			throw new OperationException("Subject not found", OperationException::ERROR_NOT_FOUND);
		}
		
		return UserDataObject::fromModel($subject);
	}
	
	/**
	 * @param  int  $id
	 * @return void
	 */
	public function delete(int $id): void
	{
		$subject = UserModel::query()->find($id);
		
		if (is_null($subject)) {
			throw new OperationException("Subject not found", OperationException::ERROR_NOT_FOUND);
		}
		$subject->delete();
	}
	
}
