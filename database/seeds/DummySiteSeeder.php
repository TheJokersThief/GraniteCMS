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

		App\Capability::create(['capability_name' => 'view_settings', 'capability_min_level' => 1, 'site' => 1]);
		App\Capability::create(['capability_name' => 'edit_settings', 'capability_min_level' => 1, 'site' => 1]);
		App\Capability::create(['capability_name' => 'create_settings', 'capability_min_level' => 1, 'site' => 1]);
		App\Capability::create(['capability_name' => 'delete_settings', 'capability_min_level' => 1, 'site' => 1]);

		App\Capability::create(['capability_name' => 'view_menus', 'capability_min_level' => 1, 'site' => 1]);
		App\Capability::create(['capability_name' => 'edit_menus', 'capability_min_level' => 1, 'site' => 1]);
		App\Capability::create(['capability_name' => 'create_menus', 'capability_min_level' => 1, 'site' => 1]);
		App\Capability::create(['capability_name' => 'delete_menus', 'capability_min_level' => 1, 'site' => 1]);

		App\Capability::create(['capability_name' => 'view_pages', 'capability_min_level' => 4, 'site' => 1]);
		App\Capability::create(['capability_name' => 'edit_pages', 'capability_min_level' => 3, 'site' => 1]);
		App\Capability::create(['capability_name' => 'create_pages', 'capability_min_level' => 3, 'site' => 1]);
		App\Capability::create(['capability_name' => 'delete_pages', 'capability_min_level' => 3, 'site' => 1]);

		App\Capability::create(['capability_name' => 'edit_users', 'capability_min_level' => 2, 'site' => 1]);
		App\Capability::create(['capability_name' => 'edit_user_roles', 'capability_min_level' => 2, 'site' => 1]);
		App\Capability::create(['capability_name' => 'edit_settings', 'capability_min_level' => 2, 'site' => 1]);
		App\Capability::create(['capability_name' => 'edit_capabilities', 'capability_min_level' => 1, 'site' => 1]);

		App\Setting::Create(['setting_name' => 'public_registration', 'setting_value' => 'no', 'site' => 1]);

		// Create Base Menus (top-level menus)
		App\MenuItem::Create(['name' => 'CMS Menu', 'link' => '/cms', 'parent' => 0, 'site' => 1]); // ID = 1
		App\MenuItem::Create(['name' => 'Main Menu', 'link' => '/', 'parent' => 0, 'site' => 1]); // ID = 2
		App\MenuItem::Create(['name' => 'Footer Menu', 'link' => '/', 'parent' => 0, 'site' => 1]); // ID = 3

		// Some basic menus:
		//
		// PAGES
		$page_menu_id = App\MenuItem::Create(['name' => 'Pages', 'link' => '#!', 'parent' => 1, 'site' => 1]); // Blank Link
		App\MenuItem::Create(['name' => 'All Pages', 'link' => '/cms/pages', 'parent' => $page_menu_id->id, 'site' => 1]);
		App\MenuItem::Create(['name' => 'Add Page', 'link' => '/cms/pages/create', 'parent' => $page_menu_id->id, 'site' => 1]);

		// ADMINISTRATION
		App\MenuItem::Create(['name' => 'Administration', 'link' => '/cms/menus', 'parent' => 1, 'site' => 1]); // Blank Link
		App\MenuItem::Create(['name' => 'Settings', 'link' => '/cms/settings', 'parent' => 1, 'site' => 1]);

		$users = factory(App\User::class, 3)->create()->each(function ($user) {
			// For every user, create 50 dummy content pages
			for ($i = 0; $i < 50; $i++) {
				$user->pages()
					->save(
						factory(App\Page::class)->make()
					);
			}
		});

		App\UserSocial::create([
			'user_id' => 1,
			'social_id' => '10212268867581946',
			'provider' => 'facebook',
			'site' => 1,
		]);

		App\UserSocial::create([
			'user_id' => 1,
			'social_id' => '54655541',
			'provider' => 'twitter',
			'site' => 1,
		]);
	}
}
