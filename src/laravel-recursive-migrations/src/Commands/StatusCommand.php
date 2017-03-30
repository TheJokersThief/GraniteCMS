<?php

namespace ThibaudDauce\LaravelRecursiveMigrations\Commands;

use Illuminate\Database\Console\Migrations\StatusCommand as BaseStatusCommand;
use ThibaudDauce\LaravelRecursiveMigrations\RecursiveMigrationCommand;

class StatusCommand extends BaseStatusCommand
{
    use RecursiveMigrationCommand;
}
