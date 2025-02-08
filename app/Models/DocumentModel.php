<?php
declare(strict_types=1);

namespace App\Models;

use App\Filters\Trait\EloquentFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Created by PhpStorm.
 * Filename: DocumentModel.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 20:37
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 *
 * @property int                 $id
 * @property int                 $user_id
 * @property string              $title
 * @property ?string             $description
 * @property string              $file_name
 * @property string              $file_path
 * @property int                 $file_size
 * @property ?string             $file_type
 * @property Carbon              $last_edited_at
 * @property Carbon              $created_at
 *
 * @property UserModel|BelongsTo $user
 */
class DocumentModel extends Model
{
	use HasFactory, EloquentFilterTrait, SoftDeletes;
	
	protected $table    = 'documents';
	protected $fillable = [
		'user_id',
		'title',
		'description',
		'file_name',
		'file_path',
		'file_size',
		'file_type',
		'last_edited_at',
	];
	
	protected $casts = [
		'last_edited_at' => 'datetime',
	];
	
	#region Relations
	public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(UserModel::class, 'user_id', 'id');
	}
	#endregion
}
