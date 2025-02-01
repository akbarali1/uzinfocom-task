<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

final class HomeController extends Controller
{
	public function __construct() {}
	
	public function home(): View
	{
		return view('home');
	}
}
