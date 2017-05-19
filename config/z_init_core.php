<?php

use App\Core\Capabilities;
use App\Core\Injection;

// init Core components
return [
    'capabilities' => new Capabilities(),


    // Should always be last
    'injection' => new injection();
];
