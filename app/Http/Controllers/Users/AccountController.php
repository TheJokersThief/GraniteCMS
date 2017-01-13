<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SiteController;
use App\User;
use App\UserSocial;
use Auth;
use Illuminate\Http\Request;
use Image;

class AccountController extends Controller {
	public function account() {
		return view('pages.singles.account')->with([
			'user' => Auth::user(),
			'providers' => UserSocial::where('user_id', Auth::id())->pluck('provider'),
		]);
	}

	public function uploadProfileImage(Request $request) {
		$site = SiteController::getSite();
		$field_name = "avatar_file";
		if ($request->hasFile($field_name)) {

			$folder_name = 'profile_images';

			$filename = $request->file($field_name)->getClientOriginalName();
			$relative_path = "images/" . $site . "/" . $folder_name;
			$path = storage_path($relative_path);
			$file_path = $path . '/' . $filename;

			if (!file_exists($path)) {
				mkdir($path, 0755, true);
			}

			Image::make($request->file($field_name))
				->fit(500, 500)->save($file_path);

			$user = Auth::user();
			$user->profile_picture = $relative_path . '/' . $filename;
			$user->save();
			return redirect()->route('cms-account');
		}
	}
}
