<?php

namespace App\Http\Middleware;

use App\Http\Controllers\SiteController;
use Closure;

class Site
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $site = SiteController::getSite();

            if (file_exists(realpath(str_replace('.', '_', base_path('sites/' . $site . '/theme/routes/')) . 'web.php'))) {
                $request->site = $site;
                return $next($request);
            } else {
                abort(404);
            }
        }
    }
}
