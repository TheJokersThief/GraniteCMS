<?php

namespace ThibaudDauce\LaravelRecursiveMigrations\Commands;

use ThibaudDauce\LaravelRecursiveMigrations\RecursiveMigrationCommand;
use Illuminate\Database\Console\Migrations\RollbackCommand as BaseRollbackCommand;

class RollbackCommand extends BaseRollbackCommand
{
    use RecursiveMigrationCommand;
}
