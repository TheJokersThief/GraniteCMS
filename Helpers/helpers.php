<?php

include_once 'helpers_errors.php';
include_once 'helpers_pages.php';
include_once 'helpers_menus.php';
include_once 'helpers_encryption.php';
include_once 'helpers_sites.php';
include_once 'helpers_api.php';

/**
 * Return a setting's value
 * @param  string $key
 * @return string
 */
function setting($key)
{
    $setting = \App\Setting::where('setting_name', $key)->firstOrFail();
    return $setting->setting_value;
}

/**
 * Return default profile image if no image supplied
 * @param  string $profile_picture
 * @return string
 */
function defaultProfile($profile_picture = null)
{
    if ($profile_picture == null) {
        return asset('images/default-profile.jpg');
    } else {
        return asset($profile_picture);
    }
}
