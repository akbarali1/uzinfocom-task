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
	public readonly int $userId;
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
	
	private function setConfig(array $config): void
	{
		$config["token"] = JWT::encode($config, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
		$this->config    = $config;
	}
	
	public function setCreateConfig(): static
	{
		$directUrl   = str_replace(config('office.public_url'), config('office.local_url'), route('document.download', ['documentId' => $this->id]));
		$callbackUrl = str_replace(config('office.public_url'), config('office.local_url'), route('document.callback', ['documentId' => $this->id]));
		$config      = [
			"type"         => request()->get("type", "desktop"),
			"documentType" => $this->_data->documentType,
			"document"     => [
				"title"         => $this->_data->fileName,
				"url"           => $directUrl,
				"directUrl"     => "",
				"fileType"      => $this->_data->fileType,
				"key"           => $this->_data->key,
				"info"          => [
					"owner"    => $this->_data->user->name ?? "Unknown",
					"uploaded" => $this->_data->createdAt->format('d.m.Y'),
					//"favorite" => true,
				],
				"permissions"   => [
					"comment"              => true,
					"copy"                 => true,
					"download"             => true,
					"edit"                 => true,
					"print"                => true,
					"fillForms"            => true,
					"modifyFilter"         => true,
					"modifyContentControl" => true,
					"review"               => false,
					"chat"                 => false,
					"reviewGroups"         => false,
					"commentGroups"        => false,
					"userInfoGroups"       => false,
					"protect"              => false,
				],
				"referenceData" => [
					"fileKey" => "d_".$this->id,
					"key"     => (string) $this->_data->key,
					//"instanceId" => 'http://localhost:8005',
				],
			],
			"editorConfig" => [
				//"actionLink"    => 'https://google.com/',
				"mode"          => 'edit',
				"lang"          => "en",
				"callbackUrl"   => $callbackUrl,
				"createUrl"     => url(),
				"coEditing"     => null,
				//				"createUrl"     => $user->id != "uid-0" ? $createUrl : null,
				//				"templates"     => $user->templates ? $templates : null,
				"user"          => [  // the user currently viewing or editing the document
					"id"    => 'u-'.$this->userId,
					"name"  => $this->_data->user->name,
					"group" => null,
					"image" => 'https://uzhackersw.uz/images/cat.jpg',
				],
				"embedded"      => [  // the parameters for the embedded document type
					"saveUrl"       => $directUrl,
					"embedUrl"      => $directUrl,
					"shareUrl"      => $directUrl,
					"toolbarDocked" => "top",  // the place for the embedded viewer toolbar (top or bottom)
				],
				"customization" => [                                                                                                                                                                                                                                                                                                                                                                                                                                                    // the parameters for the editor interface
					"about"      => true,
					// the About section display
					"comments"   => true,
					"feedback"   => false,  // the Feedback & Support menu button display
					// adds the request for the forced file saving to the callback handler when saving the document
					"forcesave"  => true,
					"autosave"   => true,
					"submitForm" => true,  // if the Submit form button is displayed or not
					// settings for the Open file location menu button and upper right corner button
					"goback"     => [
						"blank" => false,
						"text"  => "Open file location",
						"url"   => route('document.index'),
					],
				],
			],
		];
		
		$this->setConfig($config);
		
		return $this;
	}
	
	public function setEditConfig(): static
	{
		$directUrl   = str_replace(config('office.public_url'), config('office.local_url'), route('document.download', ['documentId' => $this->id]));
		$callbackUrl = str_replace(config('office.public_url'), config('office.local_url'), route('document.callback', ['documentId' => $this->id]));
		$config      = [
			"type"         => request()->get("type", "desktop"),
			"documentType" => $this->_data->documentType,
			"document"     => [
				"title"         => $this->_data->fileName,
				"url"           => $directUrl,
				"fileType"      => $this->_data->fileType,
				"key"           => $this->_data->key.time(),
				"info"          => [
					"owner"    => $this->_data->user->name ?? "Unknown",
					"uploaded" => $this->_data->createdAt->format('d.m.Y'),
					//"favorite" => true,
				],
				"permissions"   => [
					"comment"              => true,
					"copy"                 => true,
					"download"             => true,
					"edit"                 => true,
					"print"                => true,
					"fillForms"            => true,
					"modifyFilter"         => true,
					"modifyContentControl" => true,
					"review"               => false,
					"chat"                 => false,
					"reviewGroups"         => false,
					"commentGroups"        => false,
					"userInfoGroups"       => false,
					"protect"              => false,
				],
				"referenceData" => [
					"fileKey" => "d_".$this->id,
					"key"     => (string) $this->_data->key,
				],
			],
			"editorConfig" => [
				//"actionLink"    => !$request->has("actionLink") ? null : json_decode($request->get('actionLink')),
				"mode"          => 'edit',
				"lang"          => "en",
				"callbackUrl"   => $callbackUrl,
				"createUrl"     => route('document.create'),
				"coEditing"     => null,
				"user"          => [  // the user currently viewing or editing the document
					"id"    => 'u-'.$this->userId,
					"name"  => $this->_data->user->name,
					"group" => null,
					"image" => 'https://uzhackersw.uz/images/cat.jpg',
				],
				"embedded"      => [  // the parameters for the embedded document type
					"saveUrl"       => $directUrl,
					"embedUrl"      => $directUrl,
					"shareUrl"      => $directUrl,
					"toolbarDocked" => "top",  // the place for the embedded viewer toolbar (top or bottom)
				],
				"customization" => [                                                                                                                                                                                                                                                                                                                                                                                                                                                    // the parameters for the editor interface
					"about"      => true,
					// the About section display
					"comments"   => true,
					"feedback"   => false,  // the Feedback & Support menu button display
					// adds the request for the forced file saving to the callback handler when saving the document
					"forcesave"  => true,
					"autosave"   => true,
					"submitForm" => true,  // if the Submit form button is displayed or not
					// settings for the Open file location menu button and upper right corner button
					"goback"     => [
						"blank" => false,
						"text"  => "Open file location",
						"url"   => route('document.index'),
					],
				],
			],
		];
		
		$this->setConfig($config);
		
		return $this;
	}
	
	public function setViewConfig(): static
	{
		$directUrl   = str_replace(config('office.public_url_office'), config('office.local_url_office'), route('document.download', ['documentId' => $this->id]));
		$callbackUrl = str_replace(config('office.public_url'), config('office.local_url'), route('document.callback', ['documentId' => $this->id]));
		$config      = [
			"type"         => request()->get("type", "desktop"),
			"documentType" => $this->_data->documentType,
			"document"     => [
				"title"       => $this->_data->fileName,
				"url"         => $directUrl,
				"fileType"    => $this->_data->fileType,
				"key"         => $this->_data->key.time(),
				"info"        => [
					"owner"    => $this->_data->user->name ?? "Unknown",
					"uploaded" => $this->_data->createdAt->format('d.m.Y'),
					//"favorite" => true,
				],
				"permissions" => [
					"comment"      => false,
					"copy"         => true,
					"download"     => true,
					"edit"         => false,
					"chat"         => false,
					"print"        => true,
					"review"       => false,
					"modifyFilter" => true,
				],
			],
			"editorConfig" => [
				"actionLink"    => 'https://uzhackersw.uz',
				"mode"          => 'edit',
				"lang"          => "en",
				"callbackUrl"   => $callbackUrl,
				"coEditing"     => null,
				"user"          => [  // the user currently viewing or editing the document
					"id"    => 'u-'.$this->userId,
					"name"  => $this->_data->user->name,
					"group" => null,
					"image" => 'https://uzhackersw.uz/images/cat.jpg',
				],
				"embedded"      => [  // the parameters for the embedded document type
					"saveUrl"       => $directUrl,
					"embedUrl"      => $directUrl,
					"shareUrl"      => $directUrl,
					"toolbarDocked" => "top",  // the place for the embedded viewer toolbar (top or bottom)
				],
				"customization" => [                                                                                                                                                                                                                                                                                                                                                                                                                                                    // the parameters for the editor interface
					"about"      => true,
					"comments"   => true,
					"feedback"   => false,  // the Feedback & Support menu button display
					// adds the request for the forced file saving to the callback handler when saving the document
					"forcesave"  => true,
					"autosave"   => true,
					"submitForm" => true,  // if the Submit form button is displayed or not
					// settings for the Open file location menu button and upper right corner button
					"goback"     => [
						"blank" => false,
						"text"  => "Open file location",
						"url"   => route('document.index'),
					],
				],
			],
		];
		
		$this->setConfig($config);
		
		return $this;
	}
	
	
}
