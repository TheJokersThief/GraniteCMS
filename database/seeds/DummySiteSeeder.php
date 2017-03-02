<?php

use App\Helpers\NewSite;
use Illuminate\Database\Seeder;

class DummySiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $site = new NewSite('granitecms.dev');
        $siteID = $site->create();

        App\Alias::create(['domain' => 'granitecms.dev', 'alias' => 'granite.sysadmin.ie']);

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
            'site' => $siteID,
        ]);

        App\UserSocial::create([
            'user_id' => 1,
            'social_id' => '54655541',
            'provider' => 'twitter',
            'site' => $siteID,
        ]);
    }
}
