<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		//
	}
	
	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		if (env('APP_URL')) {
			URL::forceRootUrl(env('APP_URL'));
		}
		
		if ($this->app->isProduction()) {
			URL::forceScheme('https');
		}
		if (!$this->app->isProduction()) {
			DB::connection()->enableQueryLog();
		}
		Paginator::useBootstrap();
		
	}
}
