<?php

namespace ThibaudDauce\LaravelRecursiveMigrations\Commands;

use Illuminate\Database\Console\Migrations\ResetCommand as BaseResetCommand;
use ThibaudDauce\LaravelRecursiveMigrations\RecursiveMigrationCommand;

class ResetCommand extends BaseResetCommand
{
    use RecursiveMigrationCommand;
}
