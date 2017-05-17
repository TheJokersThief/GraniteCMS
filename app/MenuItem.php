<?php

namespace App;

use App\Scopes\SiteScope;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = "menus";

    protected $fillable = [
        'name',
        'link',
        'page',
        'parent',
    ];

    protected $hidden = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SiteScope);
    }

    /**
     * Retrieve children of a menu item
     * @param  Object         $query
     * @param  integer         $parent_id
     * @return Query Object
     */
    public function scopeChildrenOf($query, $parent_id)
    {
        return $query->where('parent', $parent_id)->get();
    }
}
