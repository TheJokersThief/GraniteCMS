<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Alias;
use App\Site;

// use App\Http\Requests;

class SiteController extends Controller
{
    /**
     * Return numeric site ID for given domain name
     * @param  string $site_name Domain name
     * @return int
     */
    public static function getSiteID($site_name)
    {
        $site_name = self::safeDomainName($site_name, 'reverse');
        $site = Site::where('domain', $site_name)->first();

        if ($site != null) {
            return Site::where('domain', $site_name)->first()->id;
        } else {
            $alias = Alias::where('alias', $site_name)->first();
            if ($alias != null) {
                return self::getSiteID($alias->domain);
            } else {
                throw new \Exception("No domain by that name exists in the database.");
            }
        }
    }

    /**
     * Get the current site's friendly domain name
     *
     * If it's executed in the kernel, the DB isn't ready yet
     * so we can't check against the alias unfortunately.
     * @param  boolean $kernel Whether the function is executed in the kernel or not
     * @return string
     */
    public static function getSite($kernel = false)
    {
        // Check if request is from apache
        if (isset($_SERVER['HTTP_HOST'])) {
            $site = self::safeDomainName($_SERVER['HTTP_HOST']);

            if (!$kernel) {
                $alias = Alias::where('alias', $_SERVER['HTTP_HOST'])->first();
                if ($alias != null) {
                    $site = self::safeDomainName($alias->domain);
                }
            }

            return $site;
        } else {
            return null;
        }
    }

    /**
     * Determines the absolute path in the system to the current site
     * @return string
     */
    public static function getSitePath()
    {
        return realpath(base_path('sites/' . SiteController::getSite()));
    }

    /**
     * Determines the absolute path to the .env file that should be used.
     *
     * This allows individual sites to use their own .env files
     * @param  string $site
     * @return string
     */
    public static function getEnvPath($site = null)
    {
        $site = ($site == null) ? self::getSite(true) : $site;
        $path = base_path() . "/sites/" . $site . "/";
        if ($site == null || !file_exists($path . '.env')) {
            return base_path();
        } else {
            return $path;
        }
    }

    /**
     * During command-line execution, determine which site to use by
     * using a --site flag
     * @return string
     */
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

    /**
     * Transform domain name into directory-friendly structure
     * @param  string $domain    Domain name
     * @param  string $direction Operation direction
     *                               Forward: perform the normal transformation
     *                               Reverse: perform the opposite transformation
     * @return string
     */
    public static function safeDomainName($domain, $direction = 'forward')
    {
        switch ($direction) {
            case 'forward':
                $domain = str_replace('.', '_', $domain);
                break;

            case 'reverse':
                $domain = str_replace('_', '.', $domain);
                break;
        }

        return $domain;
    }
}
