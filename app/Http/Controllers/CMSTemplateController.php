<?php

namespace App\Http\Controllers;

use App\Helpers\CRUDBuilder;
use App\Http\Controllers\SiteController;
use Auth;
use DB;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use View;

class CMSTemplateController extends Controller {

	private $data = "";

	public function __construct() {
		$page = \Route::current()->getParameter('page');
		$config_path = $this->determineConfigPath($page);
		$this->data = $this->processTemplate($config_path);
	}

	/**
	 * Display all entries in the table
	 * @param  string $page
	 * @return View
	 */
	public function index($page) {
		if (Auth::user()->can("view_{$page}")) {
			$shortlist = $this->getShortlist();
			$table = $this->getTable();
			$key = $this->getKey();
			$where = $this->getWhere();
			$order_by = $this->getOrderBy();

			// Select the key at the very minimum
			$items = DB::select("SELECT {$key}, {$shortlist} FROM {$table} {$where} {$order_by}");

			$this->determineViewPath('index', $page);

			$return_data = [
				'items' => $items,
				'shortlist' => $this->data['shortlist'],
				'page' => $page,
				'meta_info' => $this->data,
			];

			return view('pages.index', $return_data);
		} else {
			return back()->withErrors(['message' => 'You don\'t have permission to do that']);
		}
	}

	/**
	 * Show form to create new entry
	 * @param  string $page
	 * @return View
	 */
	public function create($page) {

	}

	/**
	 * Store an entry in the database
	 * @param  Request $request
	 * @param  string  $page
	 * @return route()
	 */
	public function store(Request $request, $page) {
		if (Auth::user()->can('create_' . $page)) {
			$table = $this->getTable();

			$form = new CRUDBuilder($this->data['fields']);
			$set_values = $form->processPostRequest($request);

			$id = DB::table($table)->insertGetId($set_values);

			return route('template-edit', ['page' => $page, 'encrypted_id' => $id]);
		} else {
			return back()->withErrors(['message' => 'You don\'t have permission to do that']);
		}
	}

	/**
	 * Display an individual entry
	 * @param  string 	$page
	 * @param  integer 	$id
	 * @return View
	 */
	public function show($page, $id) {

	}

	/**
	 * Display form to update an entry
	 * @param  string 	$page
	 * @param  int 		$id
	 * @return View
	 */
	public function edit($page, $id) {

		$form = new CRUDBuilder($this->data['fields']);

		$output = $form->render();

		if (Auth::user()->can("edit_{$page}")) {
			$table = $this->getTable();
			$key = $this->getKey();

			// Select the key at the very minimum
			$items = DB::select("SELECT * FROM {$table} WHERE {$key} = $id");

			$this->determineViewPath('edit', $page);

			$return_data = [
				'items' => $items,
				'page' => $page,
				'meta_info' => $this->data,
				'form' => $output,
			];

			return view('pages.edit', $return_data);
		} else {
			return back()->withErrors(['message' => 'You don\'t have permission to do that']);
		}
	}

	/**
	 * Update entry in the database
	 * @param  Request 		$request
	 * @param  string  		$page
	 * @param  enc(int)  	$encrypted_id
	 * @return route()
	 */
	public function update(Request $request, $page, $encrypted_id) {
		if (Auth::user()->can('edit_' . $page)) {
			try {
				// delete the item from the database
				$id = decrypt($encrypted_id);

				$table = $this->getTable();

				$form = new CRUDBuilder($this->data['fields']);
				$set_values = $form->processPostRequest($request);

				DB::table($table)
					->where($key, $id)
					->update($set_values);

				return route('template-edit', ['page' => $page, 'encrypted_id' => $id]);
			} catch (DecryptException $e) {
				return back()->withErrors(['message' => 'Could not decrypt item key']);
			}
		} else {
			return back()->withErrors(['message' => 'You don\'t have permission to do that']);
		}
	}

	/**
	 * Delete an entry from the database
	 * @param  string 	$page
	 * @param  enc(int) $encrypted_id [description]
	 * @return back()
	 */
	public function destroy($page, $encrypted_id) {
		if (Auth::user()->can('delete_' . $page)) {

			$table = $this->getTable();
			$key = $this->getKey();

			try {
				// delete the item from the database
				$id = decrypt($encrypted_id);

				DB::delete("DELETE FROM {$table} WHERE {$key} = :id", [$id]);
				return back();
			} catch (DecryptException $e) {
				return back()->withErrors(['message' => 'Could not decrypt item key']);
			}
		} else {
			return back()->withErrors(['message' => 'You don\'t have permission to do that']);
		}
	}

	/**
	 * Get table name from config
	 * @return string
	 */
	public function getTable() {
		return $this->sanitize($this->data['table']);
	}

	/**
	 * Get primary key column from config
	 * @return string
	 */
	public function getKey() {
		return $this->sanitize($this->data['key']);
	}

	/**
	 * Get item where clause from config
	 * @return string
	 */
	public function getWhere() {
		if (isset($this->data['where'])) {
			return $this->sanitize($this->data['where']);
		} else {
			// If the where clause isn't set, return an empty
			// string by default
			return "";
		}
	}

	/**
	 * Get shortlist of important columns from the table/config
	 * @return string
	 */
	public function getShortlist() {
		if (isset($this->data['shortlist']) && !empty($this->data['shortlist'])) {
			return $this->sanitize(implode(',', $this->data['shortlist']));
		} else {
			return "*";
		}
	}

	/**
	 * Get order by clause from config
	 * @return string
	 */
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

	/**
	 * Determines the correct path to use for a view (any view can be overridden)
	 * @param  string $view The view name to retrieve
	 * @param  string $page The name of the config file (and the page)
	 * @return string       View path
	 */
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

	/**
	 * Processes the configuration file
	 * @param  string $file_location The location of the config file
	 * @return array                 JSON decoded array of config values
	 */
	private function processTemplate($file_location) {
		$file = file_get_contents($file_location);

		return json_decode($file, true);
	}

	/**
	 * Strip out unusual characters from SQL queries
	 * @param  string $input String to be sanitized
	 * @return string        Sanitized string
	 */
	public static function sanitize($input) {
		if (!preg_match('/(\?|\.|\\|\/)/', $input, $matches)) {
			return $input;
		} else {
			return back()->withErrors(['message' => 'The following characters are illegal: ? . / \ ']);
		}
	}
}
