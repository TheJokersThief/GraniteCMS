<?php

use Illuminate\Http\Request;
use Sites\gdsitesearch_sysadmin_ie\theme\controllers\TagController;

$tagController = new TagController();

Route::get('/search/{tags}', function (Request $request, $tags) use ($tagController) {
    return $tagController->search($tags);
});

Route::get('/get_site_info/{ids}', 'SiteController@batchGetSites');
