<?php
declare(strict_types=1);

namespace App\Filters;

use App\Contracts\EloquentFilterContract;
use Illuminate\Database\Eloquent\Builder;

/**
 * Created by PhpStorm.
 * Filename: UserFilter.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 10/02/2025
 * Time: 21:41
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class UserFilter implements EloquentFilterContract
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
	
	public static function getFilter(): static
	{
		return new self((int) request('user_id', 0));
	}
	
	
}

