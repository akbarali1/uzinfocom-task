<?php
declare(strict_types=1);

namespace App\ActionData;

use Akbarali\ActionData\ActionDataBase;
use Illuminate\Validation\Rules\Password;

/**
 * Created by PhpStorm.
 * Filename: StoreUserActionData.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 17:29
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class StoreUserActionData extends ActionDataBase
{
	public readonly ?int    $id;
	public readonly string  $name;
	public readonly string  $email;
	public readonly ?string $password;
	
	protected function prepare(): void
	{
		$this->rules = [
			"title"    => "required|max:100",
			"email"    => "required|email",
			'password' => ['required', 'confirmed', Password::default()->mixedCase()->letters()->symbols()->numbers()->uncompromised()],
		];
	}
	
	
}
