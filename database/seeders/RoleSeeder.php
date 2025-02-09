<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
			'document.delete',
			'document.view.own',
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
			'document.update.own',
			'document.delete.own',
		]);
		
		//		if (UserModel::query()->exists()) {
		//			$admin = UserModel::query()->first();
		//			$admin->assignRole('admin');
		//		}
	}
}
