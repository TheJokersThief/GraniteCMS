<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model {
	protected $table = "pages";

	protected $fillable = [
		'page_title',
		'page_content',
		'page_date',
		'page_status',
		'page_author',
		'page_type',
		'page_slug',
		'site',
	];

	protected $hidden = [];

	public function author() {
		return $this->belongsTo('App\User', 'page_author');
	}

	public function site() {
		return $this->belongsTo('App\Site', 'site');
	}
}
