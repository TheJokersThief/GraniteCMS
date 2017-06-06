<?php

namespace App\Core;

use App\Http\Controllers\SiteController;
use App\Setting;
use Illuminate\Support\Facades\Schema;
use App\Helpers\NewSite;

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


                $siteName = getCurrentDomain();

                $isInstalled = setting($siteName . '_theme_installed');
                
                if ( $isInstalled != 'yes' ) {
                    // If the site theme hasn't been installed already

                    if( $isInstalled == null ){
                        // If the setting returns null, the skeleton of the site is missing

                        // Setup new site
                        $site = new NewSite(getCurrentURLDomain());
                        $siteID = $site->create();
                        
                        $setting = Setting::create([
                            'setting_name' => $siteName . '_theme_installed',
                            'setting_value' => 'no',
                            'site' => $siteID,
                        ]);
                    
                    } else{
                        $setting = Setting::where('setting_name', $siteName . '_theme_installed')->first();

                        // If it returns "no" then the skeleton is setup but the theme has been reset
                        $siteID = getCurrentSideID();
                    }

                    $installer = 'Sites\\' . $siteName . '\theme\Install';

                    // Check that the site theme has an installer class to call
                    if (class_exists($installer)) {
                        $install = new $installer();
                        $install->install($siteID);

                        $setting->setting_value = 'yes';
                        $setting->save();
                    }
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
