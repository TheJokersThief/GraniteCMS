<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Site;

// use App\Http\Requests;

class SiteController extends Controller {
	public static function getSiteID($site_name) {
		$site_name = str_replace('_', '.', $site_name);
		return Site::where('domain', $site_name)->first()->id;
	}
}
