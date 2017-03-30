<?php

namespace Sites\granitecms_dev\theme\controllers;

use App\Http\Controllers\Controller;
// use Auth;
use DB;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        $homepage_banners = DB::select("SELECT * FROM homepage_banners WHERE site = 1 ORDER BY display_order ASC");

        return view('granitecms_dev.theme.views.home', ['banners' => $homepage_banners]);
    }

    public function cookiePolicy(Request $request)
    {
        return true;
    }
}
