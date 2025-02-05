<?php
declare(strict_types=1);

namespace App\ViewModels;

use Akbarali\DataObject\DataObjectBase;
use Akbarali\ViewModel\BaseViewModel;
use App\DataObjects\DocumentDataObject;

/**
 * Created by PhpStorm.
 * Filename: DocumentViewModel.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 21:54
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class DocumentViewModel extends BaseViewModel
{
	public readonly int $id;
	public string       $name;
	public ?string      $email;
	public ?string      $token;
	
	protected DataObjectBase|DocumentDataObject $_data;
	
	protected function populate(): void
	{
		$this->createdAt = $this->_data->createdAt->format('d.m.Y H:i');
	}
	
}
