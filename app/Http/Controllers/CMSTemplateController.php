<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SiteController;
use Auth;
use DB;
use Illuminate\Http\Request;
use View;

class CMSTemplateController extends Controller {

	private $data = "";

	public function __construct() {
		$page = \Route::current()->getParameter('page');
		$config_path = $this->determineConfigPath($page);
		$this->data = $this->processTemplate($config_path);
	}

	public function index($page) {
		if (Auth::user()->can("view_{$page}")) {
			$shortlist = $this->getShortlist();
			$table = $this->getTable();
			$where = $this->getWhere();
			$order_by = $this->getOrderBy();
			$items = DB::select("select id, {$shortlist} from {$table} {$where} {$order_by}");

			$this->determineViewPath('index', $page);
			return view('pages.index', ['items' => $items, 'shortlist' => $this->data['shortlist'], 'page' => $page]);
		} else {
			return back()->withErrors(['message' => 'You don\'t have permission to do that']);
		}
	}
	public function create($page) {

	}
	public function store(Request $request, $page) {

	}
	public function show($id, $page) {

	}
	public function edit($id, $page) {

	}
	public function update(Request $request, $id, $page) {

	}
	public function destroy($encrypted_id, $page) {

	}

	public function getTable() {
		return $this->sanitize($this->data['table']);
	}

	public function getWhere() {
		if (isset($this->data['where'])) {
			return $this->sanitize($this->data['where']);
		} else {
			return "";
		}
	}

	public function getShortlist() {
		if (isset($this->data['shortlist']) && !empty($this->data['shortlist'])) {
			return $this->sanitize(implode(',', $this->data['shortlist']));
		} else {
			return "*";
		}
	}

	public function getOrderBy() {
		if (isset($this->data['order_by']) && !empty($this->data['order_by'])) {
			return "ORDER BY {$this->data['order_by']}";
		} else {
			return "";
		}
	}

	/**
	 * Determines the correct filepath for the config file (allowing developers
	 * to override any default configs) and whether it exists
	 * @param  string $page The name of the config file (and the page)
	 * @return mixed        Returns a string on success, null on failure
	 */
	private function determineConfigPath($page) {
		$site_config_path = SiteController::getSitePath() . '/cms/' . $page . '.json';

		if (file_exists($site_config_path)) {
			return $site_config_path;
		} else if (file_exists($config_path = base_path('cms/' . $page . '.json'))) {
			return $config_path;
		}

		return null;
	}

	private function determineViewPath($view, $page) {
		$site = SiteController::getSite();

		if (View::exists($view_path = $site . ".cms.pages." . $page . '.' . $view)) {
			return $view_path;
		} else if (View::exists($view_path = "pages." . $page . '.' . $view)) {
			return $view_path;
		} else {
			return ("pages.{$view}");
		}
	}

	private function processTemplate($file_location) {
		$file = file_get_contents($file_location);

		return json_decode($file, true);
	}

	private function sanitize($input) {
		if (!preg_match('/(\?|\.|\\|\/)/', $input, $matches)) {
			return $input;
		} else {
			return back()->withErrors(['message' => 'The following characters are illegal: ? . / \ ']);
		}
	}
}
