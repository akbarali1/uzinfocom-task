<?php
declare(strict_types=1);

namespace App\Models;

use App\Exceptions\DocumentException;
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
 * @property string              $key
 * @property ?string             $description
 * @property string              $file_name
 * @property string              $document_type
 * @property string              $file_path
 * @property int                 $file_size
 * @property ?string             $file_type
 * @property Carbon              $last_edited_at
 * @property Carbon              $created_at
 * @property Carbon              $updated_at
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
		'key',
		'file_name',
		'file_path',
		'file_size',
		'file_type',
		'last_edited_at',
	];
	
	protected $casts   = [
		'last_edited_at' => 'datetime',
	];
	protected $appends = ['document_type'];
	
	
	#region Relations
	public function user(): BelongsTo
	{
		return $this->belongsTo(UserModel::class, 'user_id', 'id');
	}
	#endregion
	
	
	/**
	 * @throws DocumentException
	 */
	private function getFileCategory($filename): string
	{
		$extensions = [
			'word'  => ['doc', 'docm', 'docx', 'dot', 'dotm', 'dotx', 'epub', 'fb2', 'fodt', 'htm', 'html', 'mht', 'mhtml', 'odt', 'ott', 'rtf', 'stw', 'sxw', 'txt', 'wps', 'wpt', 'xml'],
			'cell'  => ['csv', 'et', 'ett', 'fods', 'ods', 'ots', 'sxc', 'xls', 'xlsb', 'xlsm', 'xlsx', 'xlt', 'xltm', 'xltx', 'xml'],
			'slide' => ['dps', 'dpt', 'fodp', 'odp', 'otp', 'pot', 'potm', 'potx', 'pps', 'ppsm', 'ppsx', 'ppt', 'pptm', 'pptx', 'sxi'],
			'pdf'   => ['djvu', 'docxf', 'oform', 'oxps', 'pdf', 'xps'],
		];
		
		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		
		foreach ($extensions as $category => $extList) {
			if (in_array($ext, $extList)) {
				return $category;
			}
		}
		throw DocumentException::extensionNotFound();
	}
	
	/**
	 * @throws DocumentException
	 */
	public function getDocumentTypeAttribute(): string
	{
		return $this->getFileCategory($this->file_name);
	}
}
