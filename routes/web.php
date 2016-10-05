<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
 */

// Check if request is from apache
if (isset($_SERVER['HTTP_HOST'])) {
	try {
		// Include the given site's web routes
		include realpath(str_replace('.', '_', base_path('sites/' . $_SERVER['HTTP_HOST'] . '/theme/routes/')) . 'web.php');
	} catch (Exception $e) {
		// If the site doesn't exist, return 404
		abort(404);
	}
}

// Route::get('/', function () {
//     return view('welcome');
// });