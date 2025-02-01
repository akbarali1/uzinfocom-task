<?php
declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\ExceptionCode;

/**
 * Created by PhpStorm.
 * Filename: DocumentException.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 21:49
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class DocumentException extends InternalException
{
	
	public static function documentNotFound(): static
	{
		return static::new(
			code: ExceptionCode::UserNotFound,
		);
	}
	
}