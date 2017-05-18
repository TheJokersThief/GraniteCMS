<?php

namespace App\Helpers;

use App\Capability;
use App\Http\Controllers\SiteController;
use App\MenuItem;
use App\Setting;
use App\Site;
use App\UserRole;
use DB;
use File;

class NewSite
{

    private $domain = "";
    private $dbDetails = [];

    public function __construct($domain, $dbName = null, $dbUser = null, $dbPass = null, $dbHost = null)
    {
        $this->domain = $domain;

        if ($dbName != null && $dbUser != null && $dbPass != null) {
            $this->dbDetails = [
                'host' => ($dbHost == null) ? 'localhost' : $dbHost,
                'name' => $dbName,
                'user' => $dbUser,
                'pass' => $dbPass,
            ];
        }
    }

    public function create()
    {
        $this->files();

        if (!empty($this->dbDetails)) {
            $this->writeEnvFile(
                $this->dbDetails['host'],
                $this->dbDetails['name'],
                $this->dbDetails['user'],
                $this->dbDetails['pass']
            );
        }

        return $this->database();
    }

    public function files()
    {
        return File::copyDirectory(
            $source = base_path('sites/granitecms_dev'),
            $destination = base_path('sites/' . SiteController::safeDomainName($this->domain))
        );
    }

    public function writeEnvFile($dbHost = null, $dbName = null, $dbUser = null, $dbPass = null)
    {
        $contents = "
APP_ENV=" . env('APP_ENV') . "
APP_DEBUG=" . env('APP_DEBUG') . "
APP_LOG_LEVEL=" . env('APP_LOG_LEVEL') . "
APP_KEY=" . env('APP_KEY') . "
APP_URL=" . env('APP_URL') . "

DB_CONNECTION=" . env('DB_CONNECTION') . "
DB_PORT=" . env('DB_PORT') . "
DB_HOST=" . (($dbHost != null) ? $dbHost : env('DB_HOST')) . "
DB_DATABASE=" . (($dbName != null) ? $dbName : env('DB_DATABASE')) . "
DB_USERNAME=" . (($dbUser != null) ? $dbUser : env('DB_USERNAME')) . "
DB_PASSWORD=" . (($dbPass != null) ? $dbPass : env('DB_PASSWORD')) . "

BROADCAST_DRIVER=" . env('BROADCAST_DRIVER') . "
CACHE_DRIVER=" . env('CACHE_DRIVER') . "
SESSION_DRIVER=" . env('SESSION_DRIVER') . "
QUEUE_DRIVER=" . env('QUEUE_DRIVER') . "
REDIS_HOST=" . env('REDIS_HOST') . "
REDIS_PASSWORD=" . env('REDIS_PASSWORD') . "
REDIS_PORT=" . env('REDIS_PORT') . "
MAIL_DRIVER=" . env('MAIL_DRIVER') . "
MAIL_HOST=" . env('MAIL_HOST') . "
MAIL_PORT=" . env('MAIL_PORT') . "
MAIL_USERNAME=" . env('MAIL_USERNAME') . "
MAIL_PASSWORD=" . env('MAIL_PASSWORD') . "
MAIL_ENCRYPTION=" . env('MAIL_ENCRYPTION') . "
PUSHER_APP_ID=" . env('PUSHER_APP_ID') . "
PUSHER_KEY=" . env('PUSHER_KEY') . "
PUSHER_SECRET=" . env('PUSHER_SECRET') . "
SOCIAL_AUTH_BASE_DOMAIN=" . env('SOCIAL_AUTH_BASE_DOMAIN') . "
FACEBOOK_CLIENT_ID=" . env('FACEBOOK_CLIENT_ID') . "
FACEBOOK_CLIENT_SECRET=" . env('FACEBOOK_CLIENT_SECRET') . "
TWITTER_CLIENT_ID=" . env('TWITTER_CLIENT_ID') . "
TWITTER_CLIENT_SECRET=" . env('TWITTER_CLIENT_SECRET') . "
GITHUB_CLIENT_ID=" . env('GITHUB_CLIENT_ID') . "
GITHUB_CLIENT_SECRET=" . env('GITHUB_CLIENT_SECRET') . PHP_EOL;

        return file_put_contents(base_path('sites/' . SiteController::safeDomainName($this->domain) . '/.env'), $contents);
    }

    public function database()
    {
        $siteID = null;
        try {
            DB::beginTransaction();

            $safeDomainName = SiteController::safeDomainName($this->domain);
            $site = Site::create(['domain' => $this->domain, 'subfolder' => 'sites/' . $safeDomainName]);
            $siteID = $site->id;

            UserRole::create(['role_name' => 'administrator', 'role_level' => 1, 'site' => 1]);
            UserRole::create(['role_name' => 'owner', 'role_level' => 2, 'site' => 1]);
            UserRole::create(['role_name' => 'editor', 'role_level' => 3, 'site' => 1]);
            UserRole::create(['role_name' => 'contributor', 'role_level' => 4, 'site' => 1]);
            UserRole::create(['role_name' => 'subscriber', 'role_level' => 999, 'site' => 1]);

            $preConfigPages = [
                'settings' => [
                    'view' => 1,
                    'edit' => 1,
                    'create' => 1,
                    'delete' => 1,
                ],
                'menus' => [
                    'view' => 1,
                    'edit' => 1,
                    'create' => 1,
                    'delete' => 1,
                ],
                'pages' => [
                    'view' => 4,
                    'edit' => 3,
                    'create' => 3,
                    'delete' => 3,
                ],
                'users' => [
                    'view' => 2,
                    'edit' => 2,
                    'create' => 2,
                    'delete' => 1,
                ],
                'user_roles' => [
                    'view' => 1,
                    'edit' => 1,
                    'create' => 1,
                    'delete' => 1,
                ],
                'capabilities' => [
                    'view' => 1,
                    'edit' => 1,
                    'create' => 1,
                    'delete' => 1,
                ],

            ];

            foreach ($preConfigPages as $page => $caps) {
                Capability::create([
                    'capability_name' => 'view_' . $page, 'capability_min_level' => $caps['view'], 'site' => $siteID]);
                Capability::create([
                    'capability_name' => 'edit_' . $page, 'capability_min_level' => $caps['edit'], 'site' => $siteID]);
                Capability::create([
                    'capability_name' => 'create_' . $page, 'capability_min_level' => $caps['create'], 'site' => $siteID]);
                Capability::create([
                    'capability_name' => 'delete_' . $page, 'capability_min_level' => $caps['delete'], 'site' => $siteID]);
            }

            Setting::create(['setting_name' => 'public_registration', 'setting_value' => 'no', 'site' => $siteID]);
            Setting::create(['setting_name' => 'allow_all', 'setting_value' => '', 'site' => $siteID]);

            // Create Base Menus (top-level menus)
            $cmsMenu = MenuItem::create(['name' => 'CMS Menu', 'link' => '/cms', 'parent' => 0, 'site' => $siteID]); // ID = 1
            MenuItem::create(['name' => 'Main Menu', 'link' => '/', 'parent' => 0, 'site' => $siteID]); // ID = 2
            MenuItem::create(['name' => 'Footer Menu', 'link' => '/', 'parent' => 0, 'site' => $siteID]); // ID = 3

            // Some basic menus:
            //
            // PAGES
            $pageMenu = MenuItem::create(['name' => 'Pages', 'link' => '#!', 'parent' => $cmsMenu->id, 'site' => $siteID, 'page' => "pages"]); // Blank Link
            MenuItem::create(['name' => 'All Pages', 'link' => '/cms/pages', 'parent' => $pageMenu->id, 'site' => $siteID, 'page' => "pages"]);
            MenuItem::create(['name' => 'Add Page', 'link' => '/cms/pages/create', 'parent' => $pageMenu->id, 'site' => $siteID, 'page' => "pages"]);

            // ADMINISTRATION
            $adminMenu = MenuItem::create(['name' => 'Administration', 'link' => '#!', 'parent' => $cmsMenu->id, 'site' => $siteID, 'page' => 'settings']); // Blank Link
            MenuItem::create(['name' => 'Settings', 'link' => '/cms/settings', 'parent' => $adminMenu->id, 'site' => $siteID, 'page' => 'settings']);
            MenuItem::create(['name' => 'CMS Menus', 'link' => '/cms/menus', 'parent' => $adminMenu->id, 'site' => $siteID, 'page' => 'menus']);
            MenuItem::create(['name' => 'Users', 'link' => '/cms/users', 'parent' => $adminMenu->id, 'site' => $siteID, 'page' => 'users']);
            MenuItem::create(['name' => 'User Roles', 'link' => '/cms/user_roles', 'parent' => $adminMenu->id, 'site' => $siteID, 'page' => 'user_roles']);
            MenuItem::create(['name' => 'Permissions', 'link' => '/cms/capabilities', 'parent' => $adminMenu->id, 'site' => $siteID, 'page' => 'capabilities']);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
        }

        return $siteID;
    }
}
