<?php

namespace App\Console\Commands\User;

use App\Alias;
use App\Http\Controllers\SiteController;
use App\Site;
use App\User;
use App\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class NewUser extends Command
{

    private $site;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {--D|domain=} {--U|username=} {--F|firstName=} {--L|lastName=} {--E|email=} {--P|password=} {--R|role=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $site = ($this->option('domain') == null) ? $this->getSiteInput() : $this->option('domain');
        $site = SiteController::getSiteID($site);

        $username = ($this->option('username') == null) ? $this->ask('Username') : $this->option('username');
        $firstName = ($this->option('firstName') == null) ? $this->ask('First Name') : $this->option('firstName');
        $lastName = ($this->option('lastName') == null) ? $this->ask('Last Name') : $this->option('lastName');
        $email = ($this->option('email') == null) ? $this->ask('Email') : $this->option('email');
        $password = ($this->option('password') == null) ? $this->secret('Password') : $this->option('password');
        $role = ($this->option('role') == null) ? $this->getRoleInput($site) : $this->option('role');

        $result = User::create([
            'user_login' => $username,
            'user_first_name' => $firstName,
            'user_last_name' => $lastName,
            'user_display_name' => $firstName . ' ' . $lastName,
            'user_email' => $email,
            'user_password' => Hash::make($password),
            'user_role' => $role,
            'site' => $site,
        ]);

        $this->info('User successfully created.');
    }

    public function getChoiceInput($query, $column, $prompt)
    {
        $options = $query->pluck($column)->toArray();
        $choice = $this->choice($prompt, $options, null);

        return $choice;
    }

    private function getSiteInput()
    {
        $query = Site::all()->merge(Alias::select('alias as domain')->get());
        return $this->getChoiceInput($query, 'domain', 'Domain');
    }

    private function getRoleInput($site)
    {
        $roleName = $this->getChoiceInput(UserRole::where('site', $site)->get(), 'role_name', 'User Role');
    }
}
