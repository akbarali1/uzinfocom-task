<?php

namespace Database\Seeders;

use App\Models\RoleModel;
use App\Models\UserModel;
use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * Filename: UserCreateSeeder.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 10/02/2025
 * Time: 21:23
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
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
