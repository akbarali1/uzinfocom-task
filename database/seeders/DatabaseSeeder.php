<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		DB::table('users')->truncate();
		DB::table('roles')->truncate();
		$this->call([
			RoleSeeder::class,
			UserCreateSeeder::class,
		]);
	}
}
