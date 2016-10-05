<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
	protected $table = "settings";

	protected $fillable = [
		'setting_name',
		'setting_value',
		'site',
	];

	protected $hidden = [];

	public function site() {
		return $this->belongsTo('App\Site', 'site');
	}
}
