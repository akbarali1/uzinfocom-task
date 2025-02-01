<?php
declare(strict_types=1);

namespace App\ActionData;

use Akbarali\ActionData\ActionDataBase;

/**
 * Created by PhpStorm.
 * Filename: AuthActionData.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 16:51
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class AuthActionData extends ActionDataBase
{
	public readonly string $email;
	public readonly string $password;
	public bool            $remember = false;
	
	protected array $rules = [
		'email'    => 'required|email',
		'password' => 'required',
		'remember' => 'boolean',
	];
	
}
