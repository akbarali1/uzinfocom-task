<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use App\Models\DocumentModel;
use App\Models\UserModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * Filename: DocumentPermissionCheck.php
 * Project Name: uzinfocom-task
 * Author: akbarali
 * Date: 10/02/2025
 * Time: 21:22
 * GitHub: https://github.com/akbarali1
 * Telegram: @akbar_aka
 * E-mail: me@akbarali.uz
 */
class DocumentPermissionCheck
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  Closure(Request): (Response)  $next
	 * @throws UnauthorizedException
	 */
	public function handle(Request $request, Closure $next): Response
	{
		if (auth()->guest()) {
			throw  UnauthorizedException::forbidden();
		}
		
		/** @var UserModel $user */
		$user = $request->user();
		
		if ($user->hasRole('admin')) {
			return $next($request);
		}
		
		$documentId = (int) $request->route('id', 0);
		$routeName  = $request->route()?->getName();
		
		if ($this->hasPermission($user, $routeName)) {
			return $next($request);
		}
		
		if ($this->isUserDocumentOwner($user->id, $documentId)) {
			return $next($request);
		}
		
		throw  UnauthorizedException::unauthorized();
	}
	
	private function hasPermission(UserModel $user, string $routeName): bool
	{
		if (in_array($routeName, ['document.index', 'document.create', 'document.upload', "document.uploadFile"])) {
			return $user->hasAnyPermission([
				'document.view',
				"document.create",
				"document.upload",
				"document.view.own",
			]);
		}
		
		return false;
	}
	
	private function isUserDocumentOwner(int $userId, int $documentId): bool
	{
		return DocumentModel::query()->where('id', '=', $documentId)->where('user_id', '=', $userId)->exists();
	}
	
}
