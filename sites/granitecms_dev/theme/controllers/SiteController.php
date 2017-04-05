<?php

namespace Sites\granitecms_dev\theme\controllers;

use App\Http\Controllers\Controller;
// use Auth;
use App\Page;
use DB;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        $homepage_banners = DB::select("SELECT * FROM homepage_banners WHERE site = 1 ORDER BY display_order ASC");

        return view('granitecms_dev.theme.views.home', ['banners' => $homepage_banners]);
    }

    public function pages($page_slug)
    {
        $page = Page::where('page_slug', $page_slug)->where('page_status', 'published')->first();
        if ($page == null) {return abort('404');}

        return view('granitecms_dev.theme.views.pages-default', ['page' => $page]);
    }

    public function cookiePolicy(Request $request)
    {
        return response()->json([
            'status' => '200',
        ]);
    }
}
