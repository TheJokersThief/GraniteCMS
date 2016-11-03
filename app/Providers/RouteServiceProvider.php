<?php

namespace App\Providers;

use App\Http\Controllers\SiteController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider {
	/**
	 * This namespace is applied to your controller routes.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @return void
	 */
	public function boot() {
		//

		parent::boot();
	}

	/**
	 * Define the routes for the application.
	 *
	 * @return void
	 */
	public function map() {
		$this->mapApiRoutes();

		$this->mapWebRoutes();

		//
	}

	/**
	 * Define the "web" routes for the application.
	 *
	 * These routes all receive session state, CSRF protection, etc.
	 *
	 * @return void
	 */
	protected function mapWebRoutes() {
		Route::group([
			'middleware' => 'web',
			'namespace' => $this->namespace,
		], function ($router) {
			require base_path('routes/web.php');
		});

		$site = SiteController::getSite();

		if ($site) {
			Route::group([
				'middleware' => 'web',
				'namespace' => 'Sites\\' . $site . '\theme\controllers',
			], function ($router) use ($site) {
				$path = realpath(base_path('sites/' . $site . '/theme/routes/') . 'web.php');
				if ($path) {
					// If routes file exists, require it
					require $path;
				} else {
					// If files doesn't exist, return 404
					abort(404);
				}
			});
		}
	}

	/**
	 * Define the "api" routes for the application.
	 *
	 * These routes are typically stateless.
	 *
	 * @return void
	 */
	protected function mapApiRoutes() {
		Route::group([
			'middleware' => 'api',
			'namespace' => $this->namespace,
			'prefix' => 'api',
		], function ($router) {
			require base_path('routes/api.php');
		});

		$site = SiteController::getSite();

		if ($site) {
			Route::group([
				'middleware' => 'api',
				'namespace' => 'Sites\\' . $site . '\theme\controllers',
				'prefix' => 'api',
			], function ($router) use ($site) {
				$path = realpath(base_path('sites/' . $site . '/theme/routes/') . 'api.php');
				if ($path) {
					// If routes file exists, require it
					require $path;
				} else {
					// If files doesn't exist, return 404
					abort(404);
				}
			});
		}
	}
}
