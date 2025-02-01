<?php
declare(strict_types=1);

namespace App\DataObjects;

use Akbarali\DataObject\DataObjectBase;
use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * Filename: DocumentHistoryDataObject.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 21:58
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class DocumentHistoryDataObject extends DataObjectBase
{
	public readonly int     $id;
	public readonly int     $userId;
	public readonly int     $documentId;
	public readonly string  $title;
	public readonly ?string $description;
	public readonly ?string $filePath;
	public readonly ?string $fileSize;
	public readonly ?string $fileType;
	public readonly ?string $action;
	public readonly Carbon  $createdAt;
	
	#relations
	public readonly ?DocumentDataObject $document;
	public readonly ?UserDataObject     $user;
	
}