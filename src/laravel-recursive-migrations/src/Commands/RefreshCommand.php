<?php

namespace ThibaudDauce\LaravelRecursiveMigrations\Commands;

use Illuminate\Database\Console\Migrations\RefreshCommand as BaseRefreshCommand;
use ThibaudDauce\LaravelRecursiveMigrations\RecursiveMigrationCommand;

class RefreshCommand extends BaseRefreshCommand
{
    use RecursiveMigrationCommand;
}
