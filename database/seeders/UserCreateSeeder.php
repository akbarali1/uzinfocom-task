<?php

namespace Database\Seeders;

use App\Models\RoleModel;
use App\Models\UserModel;
use Illuminate\Database\Seeder;

class UserCreateSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$admin = UserModel::query()->create([
			'name'     => 'Akbarali',
			'email'    => 'github@akbarali.uz',
			'password' => bcrypt('password'),
		]);
		
		if (RoleModel::query()->where('name', 'admin')->exists()) {
			$admin->assignRole('admin');
		}
		
		$user = UserModel::query()->create([
			'name'     => 'User',
			'email'    => 'user@akbarali.uz',
			'password' => bcrypt('userpass'),
		]);
		
		if (RoleModel::query()->where('name', 'user')->exists()) {
			$user->assignRole('user');
		}
	}
}
