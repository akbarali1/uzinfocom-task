<?php
declare(strict_types=1);

namespace App\DataObjects;

use Akbarali\DataObject\DataObjectBase;
use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * Filename: UserDataObject.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 17:24
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class UserDataObject extends DataObjectBase
{
	public readonly int    $id;
	public readonly string $name;
	public readonly string $email;
	public readonly Carbon $createdAt;
}