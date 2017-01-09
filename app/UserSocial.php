<?php

namespace App;

use App\Scopes\SiteScope;
use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model {
	protected $table = "users_social";

	protected $fillable = [
		'social_id',
		'user_id',
		'provider',
		'site',
	];

	protected $hidden = [];

	protected static function boot() {
		parent::boot();
		static::addGlobalScope(new SiteScope);
	}

	public function users() {
		return $this->hasMany('App\User', 'user_id');
	}

	public function scopeGetSocialID($query, $social_id, $provider) {
		return $query->where('social_id', $social_id)->where('provider', $provider);
	}

	public function site() {
		return $this->belongsTo('App\Site', 'site');
	}
}
