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

// Route::get('/', function () {
//     return view('granitecms_dev.theme.views.home');
// });

Route::get('/', 'SiteController@index');
Route::get('/cookie-policy', 'SiteController@cookiePolicy')->name('cookie-policy');

Route::get('/{page}', 'SiteController@pages')->name('page');
