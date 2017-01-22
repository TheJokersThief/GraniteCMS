<?php

include_once 'helpers_pages.php';
include_once 'helpers_menus.php';
include_once 'helpers_encryption.php';

function setting($key)
{
    $setting = \App\Setting::where('setting_name', $key)->firstOrFail();
    return $setting->setting_value;
}

function defaultProfile($profile_picture = null)
{
    if ($profile_picture == null) {
        return asset('images/default-profile.jpg');
    } else {
        return asset($profile_picture);
    }
}
