<?php
declare(strict_types=1);

namespace App\ViewModels;

use Akbarali\DataObject\DataObjectBase;
use Akbarali\ViewModel\BaseViewModel;
use App\DataObjects\UserDataObject;
use Illuminate\Support\Collection;

/**
 * Created by PhpStorm.
 * Filename: UserViewModel.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 17:30
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class UserViewModel extends BaseViewModel
{
	public readonly int $id;
	public string       $name;
	public ?string      $email;
	public array        $roleNames = [];
	public array        $roleIds   = [];
	public ?string      $createdAt;
	
	/** @var Collection<RoleViewModel>|array<RoleViewModel> */
	public array|Collection $listOfRoles = [];
	
	protected DataObjectBase|UserDataObject $_data;
	
	protected function populate(): void
	{
		$this->createdAt = $this->_data->createdAt->format('d.m.Y H:i');
		
		foreach ($this->_data->roles as $role) {
			$this->roleNames[] = $role->name;
			$this->roleIds[]   = $role->id;
		}
	}
	
	public function setRolesList(Collection $listRoleList): void
	{
		$this->listOfRoles = $listRoleList->map(fn($item) => RoleViewModel::fromDataObject($item));
	}
}
