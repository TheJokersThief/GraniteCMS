<?php

namespace App\Core;

class Injection
{

    public function __construct()
    {
        $this->loadHooks();
    }

    public function loadHooks()
    {
        $hooks = config('hooks');
        $hooks->initHook('init_core');
        $hooks->execute('init_core', []);
    }
}
