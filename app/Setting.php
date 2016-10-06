<?php

namespace App;

use App\Scopes\SiteScope;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
	protected $table = "settings";

	protected $fillable = [
		'setting_name',
		'setting_value',
		'site',
	];

	protected $hidden = [];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope(new SiteScope);
	}

	public function site() {
		return $this->belongsTo('App\Site', 'site');
	}
}
