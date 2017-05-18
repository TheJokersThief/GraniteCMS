<?php

namespace App;

use App\Scopes\SiteScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_login',
        'user_first_name',
        'user_last_name',
        'user_display_name',
        'user_email',
        'password',
        'user_activation_token',
        'user_role',
        'user_registered',
        'fingerprint',
        'site',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_password',
        'user_activation_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SiteScope);
    }
    public function pages()
    {
        return $this->hasMany('App\Page', 'page_author');
    }

    public function role()
    {
        return $this->hasOne('App\UserRole', 'id', 'user_role');
    }

    public function site()
    {
        return $this->belongsTo('App\Site', 'site');
    }
}
