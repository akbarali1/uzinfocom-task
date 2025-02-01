<?php
declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

/**
 * @uses \App\Http\Controllers\HomeController::home()
 */
Route::controller(HomeController::class)->middleware('auth')->group(function () {
	Route::get('home', 'home')->name('home');
});

/**
 * @uses \App\Http\Controllers\AuthController::showLogin()
 * @uses \App\Http\Controllers\AuthController::login()
 * @uses \App\Http\Controllers\AuthController::logout()
 * @uses \App\Http\Controllers\AuthController::userCreate()
 */
Route::controller(AuthController::class)->group(function () {
	Route::get('login', 'showLogin')->name('login');
	Route::post('login', 'login');
	Route::get('logout', 'logout')->name('logout');
	Route::get('user-create', 'userCreate')->name('user.create');
});
