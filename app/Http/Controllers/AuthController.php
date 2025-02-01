<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\ActionData\AuthActionData;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

/**
 * Created by PhpStorm.
 * Filename: AuthController.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 16:51
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
final class AuthController extends Controller
{
	public function showLogin(): View|Factory|Application
	{
		return view('auth.login');
	}
	
	public function login(AuthActionData $actionData): RedirectResponse
	{
		$credentials = [
			'email'    => $actionData->email,
			'password' => $actionData->password,
		];
		
		if (auth()->attempt($credentials, $actionData->remember)) {
			return to_route('home');
		}
		
		return back()->withErrors([
			'email' => 'The provided credentials do not match our records.',
		]);
	}
	
	public function logout(): RedirectResponse
	{
		auth()->logout();
		
		return to_route('login');
	}
	
}
