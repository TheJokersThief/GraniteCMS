<?php

namespace App\Core;

use App\Http\Controllers\SiteController;
use App\Setting;
use Illuminate\Support\Facades\Schema;

class Installer
{

    public function __construct()
    {
        $this->installTheme();
    }

    public function installTheme()
    {
        try {
            if (Schema::hasTable('settings') && Schema::hasTable('sites') && Schema::hasTable('aliases')) {
                // If settings table hasn't been created yet, avoid errors

                $siteName = SiteController::getSite();
                $siteID = SiteController::getSiteID($siteName);

                $setting = Setting::where('setting_name', $siteName . '_theme_installed')->first();

                if ($setting == null) {
                    $setting = Setting::create([
                        'setting_name' => $siteName . '_theme_installed',
                        'setting_value' => 'no',
                        'site' => $siteID,
                    ]);
                }

                if (setting($siteName . '_theme_installed') == 'no') {
                    $installer = 'Sites\\' . $siteName . '\theme\Install';

                    if (class_exists($installer)) {
                        $install = new $installer();
                        $install->install();

                        $setting->setting_value = 'yes';
                        $setting->save();
                    }
                }
            }
        } catch (\Exception $e) {

        }
    }
}
