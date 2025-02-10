<?php
declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Created by PhpStorm.
 * Filename: IdFilterComponent.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 10/02/2025
 * Time: 21:58
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class IdFilterComponent extends Component
{
	
	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
		return view('components.id-filter-component');
	}
}
