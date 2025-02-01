<?php
declare(strict_types=1);

namespace App\ViewModels;

use Akbarali\ViewModel\BaseViewModel;

/**
 * Created by PhpStorm.
 * Filename: RoleViewModel.php
 * Project Name: intend-2.0
 * Author: akbarali
 * Date: 14/04/2023
 * Time: 18:13
 * Github: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class RoleViewModel extends BaseViewModel
{
	public readonly int $id;
	public string       $name;
	
	protected function populate(): void {}
	
}
