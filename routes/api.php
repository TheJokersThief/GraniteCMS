<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Check if request is from apache
if (isset($_SERVER['HTTP_HOST'])) {
	try {
		// Include the given site's API routes
		include realpath(str_replace('.', '_', base_path('sites/' . $_SERVER['HTTP_HOST'] . '/theme/routes/')) . 'api.php');
	} catch (Exception $e) {
		// If the site doesn't exist, return 404
		abort(404);
	}
}

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:api');
