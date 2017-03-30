<?php

namespace ThibaudDauce\LaravelRecursiveMigrations;

use Symfony\Component\Console\Input\InputOption;

/**
 * @see \Illuminate\Database\Console\Migrations\BaseCommand
 */
trait RecursiveMigrationCommand
{
    use Subdirectories;

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['recursive', 'r', InputOption::VALUE_NONE, 'Indicates if the migrations should be run recursively (nested directories).'],
        ]);
    }

    /**
     * Get all of the migration paths.
     *
     * @return array
     */
    protected function getMigrationPaths()
    {
        $paths = parent::getMigrationPaths();
        return $this->option('recursive') ? $this->allSubdirectories($paths) : $paths;
    }

    /**
     * Call another console command.
     *
     * @param  string  $command
     * @param  array   $arguments
     * @return int
     */
    public function call($command, array $arguments = [])
    {
        if (starts_with($command, 'migrate') and $command !== 'migrate:install') {
            $arguments['--recursive'] = $this->option('recursive');
        }

        parent::call($command, $arguments);
    }
}
