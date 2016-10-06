<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SiteScope implements Scope {
	public function apply(Builder $builder, Model $model) {
		global $request;

		if (isset($request->site) && !empty($request->site)) {
			$builder->where('site', \App\Http\Controllers\SiteController::getSiteID($request->site));
		}
	}
}