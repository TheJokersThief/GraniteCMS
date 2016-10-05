<?php

namespace Sites\granitecms_dev\theme\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomController extends Controller
{
    public function index( Request $request ){
        return view('granitecms_dev.theme.views.home');
    }
}
