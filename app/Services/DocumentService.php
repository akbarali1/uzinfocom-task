<?php
declare(strict_types=1);

namespace App\Services;

use Akbarali\DataObject\DataObjectCollection;
use App\ActionData\StoreDocumentActionData;
use App\DataObjects\DocumentDataObject;
use App\DataObjects\UserDataObject;
use App\Exceptions\DocumentException;
use App\Models\DocumentModel;
use App\Models\UserModel;
use Illuminate\Validation\ValidationException;

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
	 * @return DataObjectCollection<UserDataObject>
	 */
	public function paginate(int $page = 1, int $limit = 15, ?iterable $filters = null): DataObjectCollection
	{
		$model = DocumentModel::applyEloquentFilters($filters, ['roles'])->latest();
		$model->select(['documents.*']);
		
		$totalCount = $model->count();
		$skip       = $limit * ($page - 1);
		$items      = $model->skip($skip)->take($limit)->get();
		$items->transform(fn(DocumentModel $value) => DocumentDataObject::fromModel($value));
		
		return new DataObjectCollection($items, $totalCount, $limit, $page);
	}
	
	/**
	 * @param  StoreDocumentActionData  $actionData
	 * @throws ValidationException
	 * @return DocumentDataObject
	 */
	public function store(StoreDocumentActionData $actionData): DocumentDataObject
	{
		if (isset($actionData->id)) {
			$model = DocumentModel::query()->find($actionData->id);
			$actionData->addValidationRule('id', 'required|exists:documents,id');
		} else {
			$model = new UserModel();
		}
		$actionData->validateException();
		
		$model->fill($actionData->toArray(true));
		$model->save();
		
		return DocumentDataObject::fromModel($model);
	}
	
	/**
	 * @param  int  $id
	 * @throws DocumentException
	 * @return DocumentDataObject
	 */
	public function getDocument(int $id): DocumentDataObject
	{
		$model = DocumentModel::query()->find($id);
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
}
