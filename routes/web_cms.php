<?php
Auth::routes();

Route::get('/callback/{site}/{provider}/add', 'Auth\AuthController@forwardAddCallback');
Route::get('/callback/{site}/{provider}', 'Auth\AuthController@forwardCallback');
Route::get('/addprovider/{provider}', 'Auth\AuthController@redirectToProvider')->name('add-social-auth');

/**
 * SOCIAL AUTHENTICATION
 */
Route::group(['prefix' => 'auth'], function () {

    Route::get('/', 'Auth\AuthController@index')->name('auth');
    Route::post('/login', 'Auth\AuthController@postUsername')->name('post-username');
    Route::get('/login', 'Auth\AuthController@socialAuth')->name('auth-login');
    Route::get('/logout', 'Auth\AuthController@logout')->name('auth-logout');
    Route::get('/provider/{provider}', 'Auth\AuthController@redirectToProvider')->name('social-auth');
    Route::get('/provider/callback/{provider}/add', 'Auth\AuthController@handleAddProviderCallback');
    Route::match(['get', 'post'], '/provider/callback/{provider}', 'Auth\AuthController@handleProviderCallback')->name('social-auth-handle');

    Route::get('/magic-link/verify/{code}', 'Auth\AuthController@magicLinkVerification')->name('magic-link-verification');
    Route::get('/mobile-login/{data}', 'Auth\AuthController@mobileLogin')->name('mobile-login');
});

Route::group(['prefix' => 'cms', 'middleware' => ['auth']], function () {
    Route::get('/', 'CMSController@dashboard');

    Route::group(['prefix' => 'account'], function () {
        Route::get('/', 'Users\AccountController@account')->name('cms-account');
        Route::post('/profile_picture/upload', 'Users\AccountController@uploadProfileImage')->name('cms-account-upload-image');
    });

    /**
     * CMS CRUDBuilder
     */
    Route::get('/{page}', 'CMSTemplateController@index')->name('template-index');
    Route::get('/{page}/create/{menu_id?}/{parent_id?}', 'CMSTemplateController@create')->name('template-create');
    Route::get('/{page}/{id}', 'CMSTemplateController@show')->name('template-show');
    Route::get('/{page}/{id}/edit', 'CMSTemplateController@edit')->name('template-edit');
    Route::get('/{page}/{encrypted_id}/delete', 'CMSTemplateController@destroy')->name('template-delete');
    Route::post('/{page}/store', 'CMSTemplateController@store')->name('template-store');
    Route::post('/{page}/{encrypted_id}/update', 'CMSTemplateController@update')->name('template-update');

    Route::resource('pages', 'PageController');
});

Route::get('images/' . \App\Http\Controllers\SiteController::getSite() . '/{directory}/{image}', function ($directory, $image) {
    $path = storage_path('images/' . \App\Http\Controllers\SiteController::getSite() . '/' . $directory . '/' . $image);
    if (file_exists($path)) {
        return Response::download($path);
    }
});

// Site Assets (CSS/JS/Images)
Route::get('assets/{file}/{subfolder1?}/{subfolder2?}/{subfolder3?}',
    function ($file, $subfolder1 = null, $subfolder2 = null, $subfolder3 = null) {
        preg_match('/^.+(jpg|jpeg|gif|png|svg|css|js)/', $file, $matches);

        if (isset($matches[0])) {
            $file = $matches[0];
            $path = \App\Http\Controllers\SiteController::getSite() . '/theme/assets/';
            $path .= ($subfolder1 != null) ? $subfolder1 . '/' : '';
            $path .= ($subfolder2 != null) ? $subfolder2 . '/' : '';
            $path .= ($subfolder3 != null) ? $subfolder3 . '/' : '';
            $path .= $file;

            $headers = [];
            if ($matches[1] == 'css') {
                $headers['content-type'] = 'text/css';
            }

            return response()->download(base_path('sites/' . $path), $file, $headers);
        } else {
            return response(404);
        }
    })->name('site-asset');
