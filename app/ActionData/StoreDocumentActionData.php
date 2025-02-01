<?php
declare(strict_types=1);

namespace App\ActionData;

use Akbarali\ActionData\ActionDataBase;
use Illuminate\Http\UploadedFile;

/**
 * Created by PhpStorm.
 * Filename: StoreDocumentActionData.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 21:58
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class StoreDocumentActionData extends ActionDataBase
{
	public readonly ?int         $id;
	public readonly int          $user_id;
	public readonly string       $title;
	public readonly ?string      $description;
	public readonly UploadedFile $file;
	
	protected function prepare(): void
	{
		$this->rules = [
			"user_id"     => "required|exists:users,id",
			"title"       => "required|max:255",
			"description" => "nullable|string",
			"file"        => "required|file",
		];
	}
	
	
}
