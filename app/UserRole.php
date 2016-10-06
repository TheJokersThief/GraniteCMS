<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model {
	protected $table = "users_roles";

	protected $fillable = [
		'role_name',
		'role_level',
		'site',
	];

	protected $hidden = [];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope(new SiteScope);
	}

	public function users() {
		return $this->hasMany('App\User', 'user_role');
	}

	public function site() {
		return $this->belongsTo('App\Site', 'site');
	}
}
