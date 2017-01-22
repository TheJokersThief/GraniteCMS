<?php

namespace App\Helpers;

use App\Http\Controllers\CMSTemplateController;
use App\Http\Controllers\SiteController;
use DB;
use Form;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Image;

class CRUDBuilder
{

    use ValidatesRequests;

    private $fields = [];
    private $action = "";
    private $site = null;
    private $form = "";
    private $values;

    public function __construct($fields, $action = null)
    {
        $this->fields = $fields;
        $this->action = $action;
        $this->values = null;

        $this->site = SiteController::getSiteID(SiteController::getSite());
    }

    /**
     * Process HTML for the form
     * @return string
     */
    public function render()
    {
        if ($this->action == null) {
            return null;
        } else {
            $this->form .= $this->openForm();
            $this->processFields();
            $this->form .= $this->closeForm();
            return $this->form;
        }
    }

    /**
     * Handle a post request from the form
     * @param  Request $request
     * @return array            Array of validated values
     */
    public function processPostRequest(Request $request)
    {
        $this->validate($request, $this->getValidationRules());

        $set_values = [];
        foreach ($this->fields as $field) {

            switch ($field['type']) {
                case 'image':
                    $set_values[$field['name']] = $this->processImage($request, $field);
                    break;
                case 'password':
                    if ($request[$field['name']] != '' && $request[$field['name']] != null) {
                        $set_values[$field['name']] = $this->processPassword($request, $field);
                    }
                    break;
                default:
                    $set_values[$field['name']] = $request[$field['name']];
                    break;
            }
        }

        $set_values['site'] = $this->site;

        return $set_values;
    }

    public function addValues($values)
    {
        return $this->values = $values;
    }

    /**
     * Process and  upload images
     * @param  Request     $request
     * @param  array      $field
     * @return string           Relative path of image to site URL
     */
    private function processImage(Request $request, $field)
    {

        $site = SiteController::getSite();
        $field_name = $field['name'];
        if ($request->hasFile($field_name)) {

            if (!isset($field['store_folder'])) {
                $folder_name = 'uploads';
            } else {
                $folder_name = $field['store_folder'];
            }

            $filename = $request->file($field_name)->getClientOriginalName();
            $relative_path = "images/" . $site . "/" . $folder_name;
            $path = storage_path($relative_path);
            $file_path = $path . '/' . $filename;

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            if (!(isset($field['crop_width']) || isset($field['crop_height']))
                || ($field['crop_width'] <= 0 || $field['crop_height'] <= 0)) {
                // If the crop width/height isn't set or one of them is <= 0
                Image::make($request->file($field['name']))->save($file_path);
            } else {
                // If crop info supplied, crop and resize the image
                Image::make($request->file($field['name']))
                    ->fit($field['crop_width'], $field['crop_height'])->save($file_path);
            }

            return $relative_path . '/' . $filename;
        }
    }

    /**
     * Return a properly hashed password
     * @param  Request     $request
     * @param  array      $field
     * @return string
     */
    private function processPassword(Request $request, $field)
    {
        return Hash::make($request->$field['name']);
    }

    /**
     * Begin the form
     * @return string Opening <form> tag and CSRF token
     */
    private function openForm()
    {
        return Form::open([
            'url' => $this->action,
            'method' => 'POST',
            'data-parsley-validate',
            'class' => 'form-horizontal form-label-left ',
            'files' => true]);
    }

    /**
     * Stop the form
     * @return string Closing </form> tag
     */
    private function closeForm()
    {
        return Form::close();
    }

    /**
     * Convert config fields into HTML inputs
     * @return string HTML of form inputs
     */
    private function processFields()
    {
        foreach ($this->fields as $field) {
            if (!isset($field['title'])) {
                $field['title'] = ucwords(str_replace('_', ' ', $field['name']));
            }

            if (isset($this->values->{$field['name']})) {
                $value = $this->values->{$field['name']};
            } else {
                $value = null;
            }

            if (!isset($field['type'])) {
                $field['type'] = null;
            }

            // $this->form .= Form::label($field['name'], $field['title']);
            switch ($field['type']) {

                /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                TEXT
                ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
                case "text":
                    $this->form .= view('components.text')->with(['field' => $field, 'value' => $value]);
                    break;

                /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                DROPDOWN
                ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
                case "dropdown":
                    // Start with a blank option to display placeholder
                    $options = ['' => ''];

                    if (!isset($field['source'])) {
                        throw new \Exception('Every dropdown needs to specify a source');
                    }

                    switch ($field['source']) {
                        case 'table':
                            // add arrays to preserve numerical keys when merging arrays
                            $options = $options + $this->dropdownOptionsFromDatabase($field['table']);
                            break;

                        case 'options':
                            $options = array_merge($options, $this->dropdownOptionsFromConfig($field['options']));
                            break;
                    }
                    $this->form .= view('components.dropdown')->with(['field' => $field, 'options' => $options, 'value' => $value]);
                    break;

                /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                WySiWyG
                ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
                case "wysiwyg":
                    $this->form .= view('components.wysiwyg')->with(['field' => $field, 'value' => $value]);
                    break;

                /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                DATE
                ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
                case "date":
                    $value = new \DateTime($value);
                    $value = $value->format('Y-m-d');

                    $this->form .= view('components.date')->with(['field' => $field, 'value' => $value]);
                    break;

                case "image":
                    $this->form .= view('components.image')->with(['field' => $field, 'value' => $value]);
                    break;

                case "password":
                    $this->form .= view('components.password')->with(['field' => $field]);
                    break;
            }

            $this->form .= "\n";
        }

        $this->form .= view('components.submit');
    }

    /**
     * Process config fields and retrieve all validation rules
     * @return array
     */
    private function getValidationRules()
    {
        $validation_rules = [];
        foreach ($this->fields as $field) {
            if (isset($field['validation_rules'])) {
                $validation_rules[$field['name']] = $field['validation_rules'];
            }
        }

        return $validation_rules;
    }

    /**
     * Fetch an array of dropdwon options from a table in the database
     * @param  array $table The table info from the CMS template
     * @return array
     */
    private function dropdownOptionsFromDatabase($table)
    {
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
            $return_data[$result->{$table['key']}] = $format;
        }
        return $return_data;
    }

    private function dropdownOptionsFromConfig($options)
    {
        $return_options = [];

        foreach ($options as $option) {
            $return_options[$option] = $option;
        }

        return $return_options;
    }
}
