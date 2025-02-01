<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Created by PhpStorm.
 * Filename: HomeController.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 01/02/2025
 * Time: 16:51
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
final class HomeController extends Controller
{
	
	public function home(): View
	{
		return view('home');
	}
}
