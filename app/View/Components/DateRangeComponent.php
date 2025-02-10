<?php
declare(strict_types=1);

namespace App\View\Components;

use App\Models\DocumentModel;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Created by PhpStorm.
 * Filename: DateRangeComponent.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 10/02/2025
 * Time: 21:45
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class DateRangeComponent extends Component
{
	
	public function render(): View|Closure|string
	{
		return view('components.date-filter-component', [
			'minDate' => $this->minDate(),
			'maxDate' => $this->maxDate(),
		]);
	}
	
	private function minDate(): string
	{
		return Carbon::parse(DocumentModel::query()->min('created_at'))->format('Y-m-d') ?: date('Y-m-d');
	}
	
	private function maxDate(): string
	{
		return Carbon::parse(DocumentModel::query()->max('created_at'))->format('Y-m-d') ?: date('Y-m-d');
	}
}
