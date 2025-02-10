<?php
declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\ExceptionCode;

/**
 * Created by PhpStorm.
 * Filename: UnauthorizedException.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 10/02/2025
 * Time: 21:18
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class UnauthorizedException extends InternalException
{
	
	public static function unauthorized(): static
	{
		return static::new(
			code: ExceptionCode::Unauthorized,
		);
	}
	
	public static function forbidden(): static
	{
		return static::new(
			code: ExceptionCode::Forbidden,
		);
	}
	
}