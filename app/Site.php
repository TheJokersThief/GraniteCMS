<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model {
	protected $table = "sites";

	protected $fillable = [
		'domain',
		'subfolder',
	];

	protected $hidden = [];

	public function pages() {
		return $this->hasMany('App\Page', 'site');
	}

	public function user_roles() {
		return $this->hasMany('App\UserRole', 'site');
	}

	public function users() {
		return $this->hasMany('App\User', 'site');
	}
}
