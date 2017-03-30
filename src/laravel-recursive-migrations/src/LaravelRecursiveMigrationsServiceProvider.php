<?php

namespace ThibaudDauce\LaravelRecursiveMigrations;

use Illuminate\Support\ServiceProvider;
use ThibaudDauce\LaravelRecursiveMigrations\Commands\MigrateCommand;
use ThibaudDauce\LaravelRecursiveMigrations\Commands\RefreshCommand;
use ThibaudDauce\LaravelRecursiveMigrations\Commands\ResetCommand;
use ThibaudDauce\LaravelRecursiveMigrations\Commands\RollbackCommand;
use ThibaudDauce\LaravelRecursiveMigrations\Commands\StatusCommand;

class LaravelRecursiveMigrationsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->extend('command.migrate', function () {
            return new MigrateCommand($this->app['migrator']);
        });

        $this->app->extend('command.migrate.rollback', function () {
            return new RollbackCommand($this->app['migrator']);
        });

        $this->app->extend('command.migrate.refresh', function () {
            return new RefreshCommand;
        });

        $this->app->extend('command.migrate.reset', function () {
            return new ResetCommand($this->app['migrator']);
        });

        $this->app->extend('command.migrate.status', function () {
            return new StatusCommand($this->app['migrator']);
        });
    }
}
