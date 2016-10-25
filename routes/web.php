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

Auth::routes();

Route::group(['prefix' => 'cms', 'middleware' => ['auth']], function () {
	Route::get('/', 'CMSController@dashboard');

	Route::get('/{page}', 'CMSTemplateController@index')->name('template-index');
	Route::get('/{page}/create', 'CMSTemplateController@create')->name('template-create');
	Route::get('/{page}/{id}', 'CMSTemplateController@show')->name('template-show');
	Route::get('/{page}/{id}/edit', 'CMSTemplateController@edit')->name('template-edit');
	Route::get('/{page}/{encrypted_id}/delete', 'CMSTemplateController@destroy')->name('template-delete');
	Route::post('/{page}/store', 'CMSTemplateController@store')->name('template-store');
	Route::post('/{page}/{encrypted_id}/update', 'CMSTemplateController@update')->name('template-update');

	Route::resource('pages', 'PageController');
});

// Route::get('/', function () {
//     return view('welcome');
// });
