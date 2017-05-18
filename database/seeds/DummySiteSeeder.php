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

        $answer = $this->command->choice('Seed pages?', ['yes', 'no']);
        $users = factory(App\User::class, 3)->create()->each(function ($user) {

            if ($answer == 'yes') {
                // For every user, create 50 dummy content pages
                for ($i = 0; $i < 50; $i++) {
                    $user->pages()
                        ->save(
                            factory(App\Page::class)->make()
                        );
                }
            }
        });

        $admin = App\User::find(1);
        $password = $this->randomPassword();
        $admin->password = bcrypt($password);
        $admin->user_role = 1;
        $admin->save();

        $this->command->getOutput()->writeln("<info>| ~~~ Admin Info ~~~ |</info>");
        $this->command->getOutput()->writeln("<info>Email:</info> {$admin->user_email}");
        $this->command->getOutput()->writeln("<info>Password:</info> {$password}");

        $askSocialInfo = $this->command->choice('Would you like to add your twitter + facebook ID?', ['yes', 'no']);
        if ($askSocialInfo == 'yes') {
            $this->command->getOutput()->writeln("<info>| ~~~ Facebook ~~~ |</info>");
            $this->command->getOutput()->writeln("(Use https://findmyfbid.com/ to get your facebook ID)");
            $id = $this->command->ask('Enter your ID: ');

            App\UserSocial::create([
                'user_id' => 1,
                'social_id' => $id,
                'provider' => 'facebook',
                'site' => $siteID,
            ]);

            $this->command->getOutput()->writeln("<info>| ~~~ Twitter ~~~ |</info>");
            $this->command->getOutput()->writeln("(Use http://www.idfromuser.com/ to get your twitter ID)");
            $id = $this->command->ask('Enter your ID: ');

            App\UserSocial::create([
                'user_id' => 1,
                'social_id' => $id,
                'provider' => 'twitter',
                'site' => $siteID,
            ]);
        } else {
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

    public function randomPassword()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return str_shuffle($characters);
    }

    public function askSocialInfo()
    {
        $answer = strtolower($this->command->ask('Would you like to add your twitter + facebook ID? [y/n]'));
        if (!in_array($answer, ['y', 'n', 'yes', 'no'])) {
            return $this->askSocialInfo();
        }

        return $answer;
    }
}
