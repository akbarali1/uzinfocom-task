<?php
declare(strict_types=1);

namespace App\Filters;

use App\Contracts\EloquentFilterContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Created by PhpStorm.
 * Filename: DateRangeFilter.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 10/02/2025
 * Time: 21:52
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class DateRangeFilter implements EloquentFilterContract
{
	
	protected function __construct(
		protected ?string $dateFrom,
		protected ?string $dateTo,
	) {}
	
	public function applyEloquent(Builder $model): Builder
	{
		$table = $model->getModel()->getTable();
		
		if (isset($this->dateFrom)) {
			$dateFrom = Carbon::parse($this->dateFrom)->startOfDay();
			$model->where($table.'.date', '>=', $dateFrom);
		}
		if (isset($this->dateTo)) {
			$dateTo = Carbon::parse($this->dateTo)->endOfDay();
			$model->where($table.'.date', '<=', $dateTo);
		}
		
		return $model;
	}
	
	public static function getDateRangeFilter(): static
	{
		return new self(request('date_from'), request('date_to'));
	}
}

