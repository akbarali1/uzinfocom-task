<?php
declare(strict_types=1);

namespace App\Filters;

use App\Contracts\EloquentFilterContract;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * Created by PhpStorm.
 * Filename: ${FILE_NAME}
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 07/02/2025
 * Time: 22:54
 * Github: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class UserDocumentFilter implements EloquentFilterContract
{
	protected function __construct(
		protected int $userId = 0
	) {}
	
	
	public function applyEloquent(Builder $model): Builder
	{
		if ($this->userId === 0) {
			return $model;
		}
		$table = $model->getModel()->getTable();
		
		return $model->where($table.'.user_id', '=', $this->userId);
	}
	
	//	public static function getFilter(): static
	//	{
	//		return new self(request('user_id'));
	//	}
	
	public static function getFilterByUserRoles(User $user): static
	{
		if ($user->hasRole('admin')) {
			return new static(0);
		}
		
		return new static($user->id);
	}
	
}

