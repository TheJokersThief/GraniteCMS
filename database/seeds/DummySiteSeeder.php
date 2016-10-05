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
