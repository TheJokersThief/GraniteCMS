<?php

namespace ThibaudDauce\LaravelRecursiveMigrations\Commands;

use Illuminate\Database\Console\Migrations\MigrateCommand as BaseMigrateCommand;
use ThibaudDauce\LaravelRecursiveMigrations\RecursiveMigrationCommand;

class MigrateCommand extends BaseMigrateCommand
{
    protected $signature = 'migrate {--database= : The database connection to use.}
            {--force : Force the operation to run when in production.}
            {--path= : The path of migrations files to be executed.}
            {--pretend : Dump the SQL queries that would be run.}
            {--seed : Indicates if the seed task should be re-run.}
            {--step : Force the migrations to be run so they can be rolled back individually.}
            {--recursive : Indicates if the migrations should be run recursively (nested directories).}';

    use RecursiveMigrationCommand;
}
