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
		$user = UserModel::query()->create([
			'name'     => 'Akbarali',
			'email'    => 'github@akbararli.uz',
			'password' => bcrypt('password'),
		]);
		
		if (RoleModel::query()->where('name', 'admin')->exists()) {
			$user->assignRole('admin');
		}
	}
}
