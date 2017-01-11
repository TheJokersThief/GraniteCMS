<?php

namespace Sites\granite_sysadmin_ie\theme\controllers;

use App\Http\Controllers\Controller;
// use Auth;
use Illuminate\Http\Request;

class CustomController extends Controller {
	public function index(Request $request) {

		// $user = Auth::loginUsingId(1, true);
		return "TEST";
		return view('granite_sysadmin_ie.theme.views.home');
	}
}
