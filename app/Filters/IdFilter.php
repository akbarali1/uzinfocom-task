<?php
declare(strict_types=1);

namespace App\Filters;

use App\Contracts\EloquentFilterContract;
use Illuminate\Database\Eloquent\Builder;

/**
 * Created by PhpStorm.
 * Filename: IdFilter.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 10/02/2025
 * Time: 21:59
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class IdFilter implements EloquentFilterContract
{
	protected function __construct(
		protected int $id = 0
	) {}
	
	
	public function applyEloquent(Builder $model): Builder
	{
		if ($this->id === 0) {
			return $model;
		}
		
		$table = $model->getModel()->getTable();
		
		return $model->where($table.'.id', '=', $this->id);
	}
	
	public static function getFilter(): static
	{
		return new self((int) request('id', 0));
	}
	
	
}

