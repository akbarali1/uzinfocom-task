<?php

use Akbarali\ActionData\ActionDataException;
use App\Exceptions\InternalException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web     : __DIR__.'/../routes/web.php',
		commands: __DIR__.'/../routes/console.php',
		health  : '/up',
	)
	->withMiddleware(function (Middleware $middleware) {
		//
	})
	->withExceptions(function (Exceptions $exceptions) {
		$exceptions->render(function (InternalException|ActionDataException $e, Request $request) {
			if (url()->previous()) {
				return back()->withInput()->withErrors($e->getMessage());
			}
		});
	})->create();
