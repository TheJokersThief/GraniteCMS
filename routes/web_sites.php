<?php

Route::get(
    'images/' . \App\Http\Controllers\SiteController::getSite() . '/{directory}/{image}',
    function ($directory, $image) {
        $path = storage_path(
            'images/'
            . \App\Http\Controllers\SiteController::getSite()
            . '/' . $directory
            . '/' . $image
        );
        if (file_exists($path)) {
            return Response::download($path);
        }
    }
);

// Site Assets (CSS/JS/Images)
Route::get(
    'assets/{file}/{subfolder1?}/{subfolder2?}/{subfolder3?}',
    function ($file, $subfolder1 = null, $subfolder2 = null, $subfolder3 = null) {
        preg_match('/^.+(jpg|jpeg|gif|png|svg|css|js|ico)/', $file, $matches);

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
    }
)->name('site-asset');
