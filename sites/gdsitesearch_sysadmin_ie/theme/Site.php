<?php

namespace Sites\gdsitesearch_sysadmin_ie\theme;

use App\Scopes\SiteScope;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = "gdsitesearch_sysadmin_ie_sites";

    protected $fillable = [
        'name',
        'backend_dev',
        'frontend_dev',
        'project_managers',
        'image',
        'url',
        'site',
    ];

    protected $hidden = [];

    public function site()
    {
        return $this->belongsTo('App\Site', 'site');
    }
}
