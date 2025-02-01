<?php
declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');
Route::view('/docs/exceptions', 'exceptions')->name('docs.exceptions');
Route::get('/docs/exceptions/{code}', static fn($code) => view("exceptions.code", compact('code')))->name('docs.exceptions.code');

/**
 * @uses \App\Http\Controllers\HomeController::home()
 */
Route::controller(HomeController::class)->middleware('auth')->group(function () {
	Route::get('home', 'home')->name('home');
});

/**
 * @uses AuthController::showLogin()
 * @uses AuthController::login()
 * @uses AuthController::logout()
 * @uses AuthController::userCreate()
 */
Route::controller(AuthController::class)->group(function () {
	Route::get('login', 'showLogin')->name('login');
	Route::post('login', 'login');
	Route::get('logout', 'logout')->name('logout');
	Route::get('user-create', 'userCreate')->name('user.create');
});

/**
 * @uses UserController::index()
 * @uses UserController::create()
 * @uses UserController::store()
 * @uses UserController::edit()
 * @uses UserController::update()
 * @uses UserController::delete()
 */
Route::controller(UserController::class)->name('user.')->prefix('users')->group(function () {
	Route::get('/', 'index')->name('index');
	Route::get('/create', 'create')->name('create');
	Route::post('/store', 'store')->name('store');
	Route::get('/{id}/edit', 'edit')->name('edit');
	Route::post('/{id}/update', 'update')->name('update');
	Route::get('/{id}/delete', 'delete')->name('delete');
});
