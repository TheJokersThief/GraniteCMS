<?php

namespace Sites\gdsitesearch_sysadmin_ie\theme;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = "gdsitesearch_sysadmin_ie_tags";

    protected $fillable = [
        'tag',
        'postings',
    ];

    protected $hidden = [];

    public function scopeGetFromTags($query, $tags)
    {
        if (!is_array($tags)) {
            $tags = explode(',', $tags);
        }

        foreach ($tags as $tag) {
            $query->orWhere('tag', $tag);
        }

        return $query;
    }
}
