<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
    protected $table = "aliases";

    protected $fillable = [
        'domain',
        'alias',
    ];

    protected $hidden = [];
}
