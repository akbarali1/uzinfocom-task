<?php
/**
 * Created by PhpStorm.
 * Filename: exceptions.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 17:24
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 *
 * @see \App\Enums\ExceptionCode::getMessage() da ishlatiladi
 */
return [
	'-999'  => [
		"message"     => "Nomalum xatolik",
		"description" => "Bu xatolik turi topilmaganida chiqadi",
	],
	'-1000' => [
		"message"     => "User topilmadi",
		"description" => "Foydalanuvchi topilmadi. ",
	],
	'-1001' => [
		"message"     => "Userni saqlab bo'lmadi topilmadi",
		"description" => "User yaratish yoki yangilashda xatolik bo'lsa chiqadigan xatolik turi.",
	],
	'-1002' => [
		"message"     => "Siz so'ngi qolgan userni o'chira olmaysiz.",
		"description" => "Oxirigi user o'zini o'zi o'chira olmaydi.",
	],
	'-1003' => [
		"message"     => "Rol topilmadi",
		"description" => "Rol topilmaganda chiqadigan exception turi.",
	],
];