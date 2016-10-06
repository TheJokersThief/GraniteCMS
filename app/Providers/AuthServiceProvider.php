<?php

namespace App\Providers;

use App\Capability;
use App\UserRole;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider {
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
	public function boot() {
		$this->registerPolicies();

		foreach (Capability::all() as $capability) {
			Gate::define($capability->capability_name, function ($user) use ($capability) {
				$role = UserRole::find($user->user_role);
				return ($role->role_level < $capability->capability_min_level);
			});
		}
	}
}
