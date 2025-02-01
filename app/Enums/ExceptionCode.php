<?php
declare(strict_types=1);

namespace App\Enums;

/**
 * Created by PhpStorm.
 * Filename: ExceptionCode.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 17:24
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 *
 * @link /lang/en/exceptions.php fayiliga ham kod mazmunini kiritish kerak.
 */
enum ExceptionCode: int
{
	case DocumentNotFound = -2000;
	
	case RoleNotFound         = -1003;
	case CannotDeleteLastUser = -1002;
	case StoreUserFailed      = -1001;
	case UserNotFound         = -1000;
	case UnknownExceptionCode = -999;
	
	public function getStatusCode(): int
	{
		$value = $this->value;
		
		return match (true) {
			$value === -10000 => 404,
			$value === -20000 => 507,
			default           => 500,
		};
	}
	
	public function getMessage(): string
	{
		$key         = "exceptions.{$this->value}.message";
		$translation = trans($key);
		
		if ($key === $translation) {
			return "Something went wrong: ".$this->value;
		}
		
		return $translation;
	}
	
	public function getDescription(): string
	{
		$key         = "exceptions.{$this->value}.description";
		$translation = trans($key);
		
		if ($key === $translation) {
			return "No additional description provided: ".$this->value;
		}
		
		return $translation;
	}
	
	public static function getDescriptionByInternalCode(int $internalCode): string
	{
		$key         = "exceptions.{$internalCode}.description";
		$translation = trans($key);
		
		if ($key === $translation) {
			return "No additional description provided: ".$internalCode;
		}
		
		return $translation;
	}
	
	
	public function getDescriptionParams(array $params): string
	{
		$key         = "exceptions.{$this->value}.description";
		$translation = trans($key, $params);
		
		if ($key === $translation) {
			return "No additional description provided: ".$this->value;
		}
		
		return $translation;
	}
	
	public function getLink(): string
	{
		return route('docs.exceptions.code', [
			'code' => $this->value,
		]);
	}
	
	public static function findExceptionCode(int $code): ExceptionCode
	{
		foreach (self::cases() as $value) {
			if ($value->value === $code) {
				return $value;
			}
		}
		
		return self::UnknownExceptionCode;
	}
	
	public static function count(): int
	{
		return count(self::cases());
	}
}