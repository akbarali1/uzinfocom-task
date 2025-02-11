<?php
declare(strict_types=1);

namespace App\ActionData;

use Akbarali\ActionData\ActionDataBase;
use App\Models\UserModel;

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
	public readonly ?int    $id;
	public ?int             $userId = 0;
	public readonly string  $title;
	public readonly string  $documentType;
	public readonly ?string $description;
	
	//public readonly UploadedFile $file;
	
	protected function prepare(): void
	{
		$this->userId = $this->getUser()->id;
		$this->rules  = [
			"userId"       => "required|exists:users,id",
			"title"        => "required|max:255",
			"description"  => "nullable|string",
			"documentType" => "required|in:word,exel,ppt,pdf",
			//'file'        => 'required|file|mimes:doc,docm,docx,dot,dotm,dotx,epub,fb2,fodt,htm,html,mht,mhtml,odt,ott,rtf,stw,sxw,txt,wps,wpt,xml,csv,et,ett,fods,ods,ots,sxc,xls,xlsb,xlsm,xlsx,xlt,xltm,xltx,dps,dpt,fodp,odp,otp,pot,potm,potx,pps,ppsm,ppsx,ppt,pptm,pptx,sxi,djvu,docxf,oform,oxps,pdf,xps|max:10240',
		];
	}
	
	private function getAllExtensions(): array
	{
		return [
			'doc', 'docm', 'docx', 'dot', 'dotm', 'dotx', 'epub', 'fb2', 'fodt', 'htm', 'html', 'mht', 'mhtml', 'odt', 'ott', 'rtf', 'stw', 'sxw', 'txt', 'wps', 'wpt', 'xml',
			'csv', 'et', 'ett', 'fods', 'ods', 'ots', 'sxc', 'xls', 'xlsb', 'xlsm', 'xlsx', 'xlt', 'xltm', 'xltx',
			'dps', 'dpt', 'fodp', 'odp', 'otp', 'pot', 'potm', 'potx', 'pps', 'ppsm', 'ppsx', 'ppt', 'pptm', 'pptx', 'sxi',
			'djvu', 'docxf', 'oform', 'oxps', 'pdf', 'xps',
		];
	}
	
	public function setUser(): void
	{
		$this->user = auth()->user();
	}
	
	public function getUser(): UserModel
	{
		return parent::getUser();
	}
	
	
}
