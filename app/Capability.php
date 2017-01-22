<?php

namespace App;

use App\Scopes\SiteScope;
use Illuminate\Database\Eloquent\Model;

class Capability extends Model
{
    protected $table = "capabilities";

    protected $fillable = [
        'capability_name',
        'capability_min_level',
        'site',
    ];

    protected $hidden = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SiteScope);
    }
}
