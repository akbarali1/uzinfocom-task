<?php
declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\ExceptionCode;
use Illuminate\Database\QueryException;

/**
 * Created by PhpStorm.
 * Filename: UserException.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 17:29
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class UserException extends InternalException
{
	public static function userNotFound(): static
	{
		return static::new(
			code: ExceptionCode::UserNotFound,
		);
	}
	
	public static function storeUserFailed(QueryException $e): static
	{
		return static::new(
			code       : ExceptionCode::StoreUserFailed,
			description: $e->getMessage(),
		);
	}
	
	public static function cannotDeleteLastUser(): static
	{
		return static::new(
			code: ExceptionCode::CannotDeleteLastUser,
		);
	}
	
}