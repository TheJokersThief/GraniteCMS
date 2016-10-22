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

	/**
	 * Process HTML for the form
	 * @return string
	 */
	public function render() {
		return $this->form;
	}

	/**
	 * Handle a post request from the form
	 * @param  Request $request
	 * @return array            Array of validated values
	 */
	public function processPostRequest(Request $request) {
		$this->validate($request, $this->getValidationRules());

		$set_values = [];
		foreach ($this->fields as $field) {
			$set_values[ $field['name'] ] = $request[ $field['name'] ];
		}

		return $set_values
	}

	/**
	 * Begin the form
	 * @return string Opening <form> tag and CSRF token
	 */
	private function openForm() {
		return Form::open(['method' => 'POST', 'data-parsley-validate', 'class' => 'form-horizontal form-label-left']);
	}

	/**
	 * Stop the form
	 * @return string Closing </form> tag
	 */
	private function closeForm() {
		return Form::close();
	}

	/**
	 * Convert config fields into HTML inputs
	 * @return string HTML of form inputs
	 */
	private function processFields() {
		foreach ($this->fields as $field) {
			if (!isset($field['title'])) {
				$field['title'] = ucwords(str_replace('_', ' ', $field['name']));
			}

			// $this->form .= Form::label($field['name'], $field['title']);
			switch ($field['type']) {

			/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
							TEXT
			   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case "text":
				$this->form .= view('components.text')->with('field', $field);
				break;

			/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
						   DROPDOWN
			   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case "dropdown":
				// Start with a blank option to display placeholder
				$options = ['' => ''];

				switch ($field['source']) {
				case 'table':
					$options = array_merge($options, $this->dropdownOptionsFromDatabase($field['table']));
					break;

				case 'options':
					$options = array_merge($options, $field['options']);
					break;
				}

				$this->form .= view('components.dropdown')->with(['field' => $field, 'options' => $options]);
				break;

			/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
						   WySiWyG
			   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case "wysiwyg":
				$this->form .= view('components.wysiwyg')->with('field', $field);
				break;

			/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
							DATE
			   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case "date":
				$this->form .= view('components.date')->with('field', $field);
				break;
			}

			$this->form .= "\n";
		}
	}

	/**
	 * Process config fields and retrieve all validation rules
	 * @return array
	 */
	private function getValidationRules() {
		$validation_rules = [];
		foreach ($this->fields as $field) {
			$validation_rules[$field['name']] = $field['validation_rules'];
		}

		return $validation_rules;
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
}