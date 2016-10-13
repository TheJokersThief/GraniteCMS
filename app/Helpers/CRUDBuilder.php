<?php

namespace App\Helpers;

use App\Http\Controllers\CMSTemplateController;
use DB;
use Form;

class CRUDBuilder {

	private $fields = [];
	private $form = "";

	public function __construct($fields) {
		$this->fields = $fields;

		$this->form .= $this->openForm();
		$this->processFields();
		$this->form .= $this->closeForm();
	}

	public function render() {
		return $this->form;
	}

	private function openForm() {
		return Form::open(['method' => 'POST']);
	}

	private function closeForm() {
		return Form::close();
	}

	private function processFields() {
		foreach ($this->fields as $field) {
			if (!isset($field['title'])) {
				$field['title'] = ucwords(str_replace('_', ' ', $field['name']));
			}

			$this->form .= Form::label($field['name'], $field['title']);
			switch ($field['type']) {

			/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
							TEXT
			   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case "text":
				$this->form .= Form::text($field['name']);
				break;

			/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
						   DROPDOWN
			   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case "dropdown":
				switch ($field['source']) {
				case 'table':
					$this->form .= Form::select($field['name'], $this->dropdownOptionsFromDatabase($field['table']));
					break;

				case 'options':
					$this->form .= Form::select($field['name'], $field['options']);
					break;
				}
				break;

			/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
						   WySiWyG
			   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case "wysiwyg":
				$this->form .= $this->wysiwyg();
				break;

			/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
							SLUG
			   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case "slug":
				$this->form .= "\nSLUG";
				break;

			/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
							DATE
			   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case "date":
				$this->form .= "\nDATE";
				break;
			}

			$this->form .= "\n";
		}
	}

	/**
	 * Fetch an array of dropdwon options from a table in the database
	 * @param  array $table The table info from the CMS template
	 * @return array
	 */
	private function dropdownOptionsFromDatabase($table) {
		$columns = [];

		// Separate the fields into column -> placeholder pairs
		foreach ($table['fields'] as $column) {
			$columns[$column['column']] = $column['placeholder'];
		}

		// Strip out weird characters
		$table_name = CMSTemplateController::sanitize($table['table']);
		$where = (isset($table['where']) ? CMSTemplateController::sanitize($table['where']) : "true");
		$results = DB::select("SELECT * FROM {$table_name} WHERE {$where}");

		$return_data = [];
		foreach ($results as $result) {
			$format = "";
			if (isset($table['format'])) {
				$format = $table['format'];
				foreach ($columns as $column => $placeholder) {
					// Replace placeholders with returned data
					$format = str_replace('{' . $placeholder . '}', $result->$column, $format);
				}
			} else {
				foreach ($columns as $column => $placeholder) {
					$format .= $result->$column . ' ';
				}
			}

			// return the key and the result
			$return_data[$result->$table['key']] = $format;
		}
		return $return_data;
	}

	private function wysiwyg() {
		return view('components.wysiwyg');
	}
}