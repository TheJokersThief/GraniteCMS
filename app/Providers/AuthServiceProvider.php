<?php

namespace App\Providers;

use App\Capability;
use App\Http\Controllers\SiteController;
use App\UserRole;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (SiteController::getSite() != null) {
            // If through console, don't employ capabilities

            // on installation, this table won't exist so PHP scripts will fail
            // No capabilities are needed from the command line anyway so be grand
            if (Schema::hasTable('capabilities')) {
                foreach (Capability::all() as $capability) {
                    Gate::define($capability->capability_name, function ($user) use ($capability) {
                        $role = UserRole::find($user->user_role);

                        if ($role != null) {
                            return ($role->role_level <= $capability->capability_min_level);
                        }
                    });
                }
            }
        }
    }
}
