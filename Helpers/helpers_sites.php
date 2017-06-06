<?php

/**
 * Determine URL to individual site asset
 *     Rewrites a standard URL form to match our asset URL structure
 * @param  string $url
 * @return string
 */
function siteAsset($url)
{
    preg_match('/(.+)\/(.+)?\/?(.+)?\/?(.+)?\/?/', $url, $matches);

    if (isset($matches[1])) {
        $file = null;
        $subfolder1 = null;
        $subfolder2 = null;
        $subfolder3 = null;

        if (isset($matches[4])) {
            // 3 subfolders
            $subfolder3 = $matches[3];
            $subfolder2 = $matches[2];
            $subfolder1 = $matches[1];
            $file = $matches[4];
        } else if (isset($matches[3])) {
            // 2 subfolders
            $subfolder2 = $matches[2];
            $subfolder1 = $matches[1];
            $file = $matches[3];
        } else if (isset($matches[2])) {
            // 1 subfolder
            $subfolder1 = $matches[1];
            $file = $matches[2];
        } else {
            // no subfolders
            $file = $matches[1];
        }

        return route('site-asset', [$file, $subfolder1, $subfolder2, $subfolder3]);
    }
}

function getCurrentDomain(){
    return \App\Http\Controllers\SiteController::getSite();
}

function getCurrentURLDomain(){
    return \App\Http\Controllers\SiteController::safeDomainName(getCurrentDomain(), 'reverse');
}

function getCurrentSideID(){
    return \App\Http\Controllers\SiteController::getSiteID(getCurrentDomain());
}