<?php

namespace App;

use App\Scopes\SiteScope;
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
		'page_banner',
		'menu_id',
		'display_order',
		'parent_id',
		'site',
	];

	protected $hidden = [];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope(new SiteScope);
	}

	public function author() {
		return $this->belongsTo('App\User', 'page_author');
	}

	public function menu() {
		return $this->belongsTo('App\MenuItem', 'menu_id');
	}

	public function site() {
		return $this->belongsTo('App\Site', 'site');
	}
}
