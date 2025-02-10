<?php
declare(strict_types=1);

namespace App\ViewModels;

use Akbarali\DataObject\DataObjectBase;
use Akbarali\ViewModel\BaseViewModel;
use App\DataObjects\DocumentDataObject;
use Firebase\JWT\JWT;

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
	public ?string      $authorName;
	public ?string      $fileName;
	public int|string   $fileSize = 0;
	public ?string      $createdAt;
	public ?string      $lastEditedAt;
	public ?string      $viewUrl;
	public ?string      $editUrl;
	public ?string      $downloadUrl;
	public ?string      $deleteUrl;
	public array        $config   = [];
	
	protected DataObjectBase|DocumentDataObject $_data;
	
	protected function populate(): void
	{
		$this->createdAt    = $this->_data->createdAt->format('d.m.Y H:i');
		$this->lastEditedAt = $this->_data->lastEditedAt->format('d.m.Y H:i');
		$this->fileSize     = formatSize((int) $this->fileSize);
		
		$this->viewUrl     = route('document.view', ['id' => $this->id]);
		$this->downloadUrl = route('document.download', ['documentId' => $this->id]);
		$this->editUrl     = route('document.edit', ['id' => $this->id]);
		$this->deleteUrl   = route('document.delete', ['id' => $this->id]);
		
		$this->authorName = $this->_data?->user?->name;
	}
	
	public function setConfig(array $config): void
	{
		$config["token"] = JWT::encode($config, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
		$this->config    = $config;
	}
	
}
