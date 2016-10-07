<?php

namespace App\Http\Controllers;

class CMSController extends Controller {
	public function dashboard() {
		return view('pages.default');
	}
}
