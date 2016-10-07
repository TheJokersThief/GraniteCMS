<?php

function setting($key) {
	$setting = \App\Setting::where('setting_name', $key)->firstOrFail();
	return $setting->setting_value;
}