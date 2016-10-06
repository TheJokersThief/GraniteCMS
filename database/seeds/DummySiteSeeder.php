<?php

use Illuminate\Database\Seeder;

class DummySiteSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		App\Site::create(['domain' => 'granitecms.dev', 'subfolder' => 'sites/granitecms_dev']);

		App\UserRole::create(['role_name' => 'administrator', 'role_level' => 1, 'site' => 1]);
		App\UserRole::create(['role_name' => 'owner', 'role_level' => 2, 'site' => 1]);
		App\UserRole::create(['role_name' => 'editor', 'role_level' => 3, 'site' => 1]);
		App\UserRole::create(['role_name' => 'contributor', 'role_level' => 4, 'site' => 1]);
		App\UserRole::create(['role_name' => 'subscriber', 'role_level' => 999, 'site' => 1]);

		App\Capability::create(['capability_name' => 'view_pages', 'capability_min_level' => 4, 'site' => 1]);
		App\Capability::create(['capability_name' => 'edit_pages', 'capability_min_level' => 3, 'site' => 1]);
		App\Capability::create(['capability_name' => 'edit_users', 'capability_min_level' => 2, 'site' => 1]);
		App\Capability::create(['capability_name' => 'edit_user_roles', 'capability_min_level' => 2, 'site' => 1]);
		App\Capability::create(['capability_name' => 'edit_settings', 'capability_min_level' => 2, 'site' => 1]);
		App\Capability::create(['capability_name' => 'edit_capabilities', 'capability_min_level' => 1, 'site' => 1]);

		$users = factory(App\User::class, 3)->create()->each(function ($user) {

			for ($i = 0; $i < 50; $i++) {
				$user->pages()
					->save(
						factory(App\Page::class)->make()
					);
			}
		});
	}
}
