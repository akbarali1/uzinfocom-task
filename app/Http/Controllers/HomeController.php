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

	public function test()
	{
		$token = env('ONLYOFFICE_JWT_SECRET'); // Yuqorida yaratilgan token
		
			$url = 'https://bug-free-disco-6rxv7qxjvpqhxq5r-8080.app.github.dev/coauthoring/command';
			$data = [
				'document' => [
					'title' => 'My Document',
					'url' => 'https://example.com/document.docx'
				]
			];

			$options = [
				'http' => [
					'header'  => "Authorization: Bearer $token\r\n" .
								"Content-Type: application/json\r\n",
					'method'  => 'POST',
					'content' => json_encode($data),
				],
			];

			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			dd($result);
		echo "testing";
	}
}
