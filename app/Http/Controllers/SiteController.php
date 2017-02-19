<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Site;

// use App\Http\Requests;

class SiteController extends Controller
{
    public static function getSiteID($site_name)
    {
        $site_name = str_replace('_', '.', $site_name);
        return Site::where('domain', $site_name)->first()->id;
    }

    public static function getSite()
    {
        // Check if request is from apache
        if (isset($_SERVER['HTTP_HOST'])) {
            return str_replace('.', '_', $_SERVER['HTTP_HOST']);
        } else {
            return null;
        }
    }

    public static function getSitePath()
    {
        return realpath(base_path('sites/' . SiteController::getSite()));
    }

    public static function getEnvPath($site = null)
    {
        $site = ($site == null) ? self::getSite() : $site;
        $path = base_path() . "/sites/" . $site . "/";
        if ($site == null || !file_exists($path . '.env')) {
            return base_path();
        } else {
            return $path;
        }
    }

    public static function getSiteArg()
    {
        $args = $GLOBALS['argv'];
        $counter = 0;
        foreach ($args as $arg) {
            if (strpos($arg, '--site=') !== false) {
                unset($GLOBALS['argv'][$counter]);
                unset($GLOBALS['_SERVER']['argv'][$counter]);
                return str_replace('--site=', '', $arg);
            }

            $counter++;
        }
        return null;
    }
}
