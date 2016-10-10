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

	public static function getSite() {
		// Check if request is from apache
		if (isset($_SERVER['HTTP_HOST'])) {
			try {
				// Include the given site's web routes
				return str_replace('.', '_', $_SERVER['HTTP_HOST']);
			} catch (Exception $e) {
				// If the site doesn't exist, return 404
				abort(404);
			}
		}
	}

	public static function getSitePath() {
		return realpath(base_path('sites/' . SiteController::getSite()));
	}
}
