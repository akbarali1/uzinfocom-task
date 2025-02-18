<?php
declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');
Route::get('/healthcheck', static fn(): JsonResponse => response()->json(true))->name('healthcheck');
Route::view('/docs/exceptions', 'exceptions')->name('docs.exceptions');
Route::get('/docs/exceptions/{code}', static fn($code) => view("exceptions.code", compact('code')))->name('docs.exceptions.code');

/**
 * @uses AuthController::showLogin()
 * @uses AuthController::login()
 * @uses AuthController::logout()
 */
Route::controller(AuthController::class)->group(function () {
	Route::get('login', 'showLogin')->name('login');
	Route::post('login', 'login');
	Route::get('logout', 'logout')->name('logout');
});


Route::middleware('auth')->group(function () {
	/**
	 * @uses \App\Http\Controllers\HomeController::home()
	 * @uses \App\Http\Controllers\HomeController::test()
	 */
	Route::controller(HomeController::class)->group(function () {
		Route::get('home', 'home')->name('home');
		Route::get('test', 'test')->name('test');
	});
	
	/**
	 * @uses UserController::index()
	 * @uses UserController::create()
	 * @uses UserController::store()
	 * @uses UserController::edit()
	 * @uses UserController::update()
	 * @uses UserController::delete()
	 */
	Route::controller(UserController::class)->middleware('role:admin')->name('user.')->prefix('users')->group(function () {
		Route::get('/', 'index')->name('index');
		Route::get('/create', 'create')->name('create');
		Route::post('/store', 'store')->name('store');
		Route::get('/{id}/edit', 'edit')->name('edit');
		Route::post('/{id}/update', 'update')->name('update');
		Route::get('/{id}/delete', 'delete')->name('delete');
	});
	
	/**
	 * @uses DocumentController::index()
	 * @uses DocumentController::create()
	 * @uses DocumentController::upload()
	 * @uses DocumentController::uploadFile()
	 * @uses DocumentController::download()
	 * @uses DocumentController::callback()
	 * @uses DocumentController::edit()
	 * @uses DocumentController::view()
	 * @uses DocumentController::update()
	 * @uses DocumentController::delete()
	 * @uses DocumentController::history()
	 * @uses DocumentController::historyObj()
	 */
	Route::controller(DocumentController::class)->name('document.')->prefix('document')->group(function () {
		Route::any('/download', 'download')->name('download')->withoutMiddleware('auth'); # TODO: Bug fix for download user file
		Route::any('/callback', 'callback')->name('callback')->withoutMiddleware('auth');
		Route::any('/history', 'history')->name('history')->withoutMiddleware('auth');
		Route::any('/{id}/history-obj', 'historyObj')->name('historyObj');
		Route::get('/download-history', 'downloadHistory')->name('downloadHistory');
		
		Route::middleware('document_check')->group(function () {
			Route::get('/', 'index')->name('index');
			Route::get('/create', 'create')->name('create');
			Route::get('/upload', 'upload')->name('upload');
			Route::post('/store', 'createFile')->name('store');
			Route::post('/upload-file', 'uploadFile')->name('uploadFile');
			Route::post('/{id}/rename', 'rename')->name('rename');
			Route::get('/{id}/edit', 'edit')->name('edit');
			Route::get('/{id}/view', 'view')->name('view');
			Route::post('/{id}/update', 'update')->name('update');
			Route::get('/{id}/delete', 'delete')->name('delete');
		});
	});
});



