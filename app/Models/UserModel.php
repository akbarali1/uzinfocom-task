<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Carbon;

/**
 * Created by PhpStorm.
 * Filename: UserModel.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 16:52
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 *
 * @property int    $id
 * @property int    $name
 * @property string $email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class UserModel extends User
{
	protected $table = 'users';
}
