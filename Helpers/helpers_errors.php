<?php

function errorMsg($key)
{
    $errors = [
        'menus_incorrect_menu_argument' => '$menus must be a Collection of MenuItem.',
        'menus_no_menu' => 'There is no menu by that identifier.',

    ];

    return $errors[$key];
}
