<?php
declare(strict_types=1);

namespace App\View\Components;

use App\Models\UserModel;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Created by PhpStorm.
 * Filename: UserFilter.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 10/02/2025
 * Time: 21:29
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class UserFilter extends Component
{
	
	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
		$users = $this->getUsers();
		
		return view('components.user-filter', compact('users'));
	}
	
	/**
	 * @return array{id: int, name: string}
	 */
	private function getUsers(): array
	{
		return UserModel::query()->orderBy('name')->get()->transform(fn($user) => [
			'id'   => $user->id,
			'name' => $user->name,
		])->toArray();
	}
}
