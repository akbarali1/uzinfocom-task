<?php
declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\ExceptionCode;
use Exception;

/**
 * Created by PhpStorm.
 * Filename: InternalException.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 17:24
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class InternalException extends Exception
{
	protected string        $description;
	protected array         $langParams = [];
	protected ExceptionCode $internalCode;
	
	public static function new(
		ExceptionCode $code,
		?string $message = null,
		?string $description = null,
		?int $statusCode = null,
	): static {
		$exception = new static(
			$message ?? $code->getMessage(),
			$statusCode ?? $code->getStatusCode(),
		);
		
		$exception->internalCode = $code;
		$exception->description  = $description ?? $code->getDescription();
		
		return $exception;
	}
	
	public static function newLangParams(
		ExceptionCode $code,
		array $langParams,
		?string $message = null,
		?string $description = null,
		?int $statusCode = null,
	): static {
		$exception               = new static(
			$message ?? $code->getMessage(),
			$statusCode ?? $code->getStatusCode(),
		);
		$exception->internalCode = $code;
		$exception->langParams   = $langParams;
		$exception->description  = $description ?? $code->getDescriptionParams($langParams);
		
		return $exception;
	}
	
	public static function from(
		int $code,
		?string $message = null,
		?string $description = null,
		?int $statusCode = null,
	): static {
		$exception = new static(
			$message ?? ExceptionCode::findExceptionCode($code)->getMessage(),
			$statusCode ?? ExceptionCode::findExceptionCode($code)->getStatusCode(),
		);
		
		$exception->internalCode = ExceptionCode::findExceptionCode($code);
		$exception->description  = $description ?? ExceptionCode::findExceptionCode($code)->getDescription();
		
		return $exception;
	}
	
	public function getInternalCode(): ExceptionCode
	{
		return $this->internalCode;
	}
	
	public function checkInternalCode(ExceptionCode $code): bool
	{
		return $code === $this->getInternalCode();
	}
	
	public function getDescription(): string
	{
		return $this->description;
	}
}