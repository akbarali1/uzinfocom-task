<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Created by PhpStorm.
 * Filename: RoleSeeder.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 10/02/2025
 * Time: 21:22
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class RoleSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$permissions = [
			'document.create',
			'document.view',
			'document.upload',
			'document.update',
			'document.rename',
			'document.delete',
			'document.view.own',
			'document.rename.own',
			'document.edit.own',
			'document.update.own',
			'document.delete.own',
		];
		
		foreach ($permissions as $permission) {
			Permission::query()->firstOrCreate(['name' => $permission]);
		}
		
		$adminRole = Role::query()->firstOrCreate(['name' => 'admin']);
		$adminRole->syncPermissions($permissions);
		
		$userRole = Role::query()->firstOrCreate(['name' => 'user']);
		$userRole->syncPermissions([
			'document.create',
			'document.upload',
			'document.view.own',
			'document.edit.own',
			'document.rename.own',
			'document.update.own',
			'document.delete.own',
		]);
		
		//		if (UserModel::query()->exists()) {
		//			$admin = UserModel::query()->first();
		//			$admin->assignRole('admin');
		//		}
	}
}
