<?php

namespace App\Console\Commands;

use App\Helpers\NewSite;
use Illuminate\Console\Command;

class CreateNewSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site:create {domain} {--N|dbname=} {--U|dbuser=} {--P|dbpass=} {--H|dbhost=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new GraniteCMS site.';

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
        $domain = $this->argument('domain');
        $dbname = $this->option('dbname');
        $dbuser = $this->option('dbuser');
        $dbpass = $this->option('dbpass');
        $dbhost = $this->option('dbhost');
        $site = new NewSite($domain, $dbname, $dbuser, $dbpass, $dbhost);
        $site->create();
    }
}
