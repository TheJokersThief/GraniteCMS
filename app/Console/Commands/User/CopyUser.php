<?php

namespace App\Console\Commands\User;

use App\Alias;
use App\Http\Controllers\SiteController;
use App\Site;
use App\User;
use Illuminate\Console\Command;

class CopyUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:copy {--S|source} {--D|dest} {--U|userID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy user from one site to another';

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
        $result = $this->ensureDifferentSites();

        $source = $result[0];
        $destination = $result[1];

        $user = ($this->option('userID') == null) ? $this->getUserIDInput() : $this->option('userID');
        $user = User::where('user_login', $user)->where('site', $source)->first();

        $user = $user->replicate();
        $user->site = $destination;
        $user->save();

        $this->info('User copied successfully.');
    }

    public function getChoiceInput($query, $column, $prompt)
    {
        $options = $query->pluck($column)->toArray();
        $choice = $this->choice($prompt, $options, null);

        return $choice;
    }

    private function getSiteInput($prompt)
    {
        $query = Site::all()->merge(Alias::select('alias as domain')->get());
        return $this->getChoiceInput($query, 'domain', $prompt);
    }

    private function getUserIDInput()
    {
        return $this->getChoiceInput(User::all(), 'user_login', 'User To Copy');
    }

    private function ensureDifferentSites()
    {
        $source = ($this->option('source') == null) ? $this->getSiteInput('Source Site') : $this->option('source');
        $source = SiteController::getSiteID($source);

        $destination = ($this->option('dest') == null) ? $this->getSiteInput('Destination Site') : $this->option('dest');
        $destination = SiteController::getSiteID($destination);

        if ($source == $destination) {
            $this->error('Source and destination cannot be the same site (that includes aliases)');
            return $this->ensureDifferentSites();
        }

        return [$source, $destination];
    }
}
