<?php

use Akbarali\ActionData\ActionDataException;
use App\Exceptions\InternalException;
use App\Http\Middleware\DocumentPermissionCheck;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web     : __DIR__.'/../routes/web.php',
		commands: __DIR__.'/../routes/console.php',
		health  : '/up',
	)
	->withMiddleware(function (Middleware $middleware) {
		$middleware->alias([
			'role'               => RoleMiddleware::class,
			'permission'         => PermissionMiddleware::class,
			'role_or_permission' => RoleOrPermissionMiddleware::class,
			"document_check"     => DocumentPermissionCheck::class,
		]);
		$middleware->validateCsrfTokens(except: [
			'document/callback',
			'document/download',
			'document/upload',
		]);
	})
	->withExceptions(function (Exceptions $exceptions) {
		$exceptions->render(function (InternalException|ActionDataException $e, Request $request) {
			if (url()->previous()) {
				return back()->withInput()->withErrors($e->getMessage());
			}
		});
	})->create();
