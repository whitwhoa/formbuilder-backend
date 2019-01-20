<?php

namespace whitwhoa\FormBuilderBackend;

use Illuminate\Validation\Validator;
use JeffOchoa\ValidatorFactory;

class FormBuilderValidator {

    /**
     * @var ValidatorFactory
     */
    private $vf;
    /**
     * @var array
     */
    private $fields = [];
    /**
     * @var array
     */
    private $errors = [];


    /**
     * Optionally pass $fields as (json)string value upon instantiation
     *
     * Validate constructor.
     * @param string $fields
     */
    public function __construct(string $fields='')
    {
        $this->vf = new ValidatorFactory();
        if($fields !== ''){
            $this->validate($fields);
        }
    }

    /**
     * Determine if errors are present
     *
     * @return bool
     */
    public function hasErrors() : bool {
        return count($this->errors) > 0 ? true : false;
    }

    /**
     * Return array of error messages
     *
     * @return array
     */
    public function getErrors() : array {
        return $this->errors;
    }

    /**
     *
     *
     * @param string $fields
     */
    public function validate(string $fields) : void {
        $this->fields = json_decode($fields, true);
        foreach($this->fields as $k => $f){
            //echo $f['type'] . "\n";
            switch($f['type']){
                case 'text':
                    $this->text($f, $k);
                    break;
                case 'textarea':
                    $this->textArea($f, $k);
                    break;
                case 'select':
                    $this->select($f, $k);
                    break;
                case 'radio-group':
                    $this->radioGroup($f, $k);
                    break;
                case 'paragraph':
                    $this->paragraph($f, $k);
                    break;
                case 'number':
                    $this->number($f, $k);
                    break;
                case 'header':
                    $this->header($f, $k);
                    break;
                case 'date':
                    $this->date($f, $k);
                    break;
                case 'checkbox-group':
                    $this->checkboxGroup($f, $k);
                    break;
                default:
                    $this->errors[] = 'Form field ' . ($k + 1) . ': Unsupported form type';
            }
        }
    }

    /**
     * Validate checkbox group field. Sample input:
     *
    array (
    'required' => true, // optional
    'label' => 'Checkbox Group',
        'inline' => true, // optional
        'values' => array (
            0 => array (
                'label' => 'asdf',
                'value' => 'asdf',
                'selected' => true, // optional
            )
    )
     *
     * @param array $cg
     * @param int $key
     */
    private function checkboxGroup(array $cg, int $key) : void {
        $rules = [];
        $rules['label']         = 'sometimes|max:100';
        $rules['required']      = isset($cg['required']) ? 'boolean' : '';
        $rules['inline']        = isset($cg['inline']) ? 'boolean' : '';
        $rules['values']        = ['required', function($attribute, $value, $fail){
            foreach($value as $v){
                if(!array_key_exists('label', $v) || $v['label'] === ''){
                    return $fail('Option must be present');
                }
                if(strlen($v['label']) > 100){
                    return $fail('Option must not exceed 100 characters');
                }
                if(array_key_exists('value', $v) && strlen($v['value']) > 100){
                    return $fail('Option value must not exceed 100 characters');
                }
                if(array_key_exists('selected', $v) && $v['selected'] !== true){
                    return $fail('Selected value must be true');
                }
            }
        }];
        $v = $this->vf->make($cg, $rules);
        $this->generateErrors($v, $key);
    }

    /**
     * Validate date field. Sample input:
     *
        array (
            'required' => true, // optional
            'label' => 'Date Field',
        )
     *
     * @param array $d
     * @param int $key
     */
    private function date(array $d, int $key) : void {
        $rules = [];
        $rules['label']         = 'required|max:100';
        $rules['required']      = isset($n['required']) ? 'boolean' : '';
        $v = $this->vf->make($d, $rules);
        $this->generateErrors($v, $key);
    }

    /**
     * Validate header field. Sample input:
     *
        array (
            'subtype' => 'h1',
            'label' => 'Some Descriptive Words',
        )
     *
     * @param array $h
     * @param int $key
     */
    private function header(array $h, int $key) : void {
        $rules = [];
        $rules['label']         = 'required|max:100';
        $rules['subtype']       = 'required|in:h1,h2,h3,h4';
        $v = $this->vf->make($h, $rules);
        $this->generateErrors($v, $key);
    }

    /**
     * Validate number field. Sample input:
     *
        array (
            'required' => true, // optional
            'label' => 'Number',
            'min' => '5', // optional
            'max' => '3', // optional
        )
     *
     * @param array $n
     * @param int $key
     */
    private function number(array $n, int $key) : void {
        $rules = [];
        $rules['label']         = 'required|max:100';
        $rules['required']      = isset($n['required']) ? 'boolean' : '';
        $rules['min']           = isset($n['min']) ? 'integer' : '';
        $rules['max']           = isset($n['max']) ? 'integer' : '';
        $v = $this->vf->make($n, $rules);
        $this->generateErrors($v, $key);
    }

    /**
     * Validate paragraph field. Sample input:
     *
        array (
            'label' => 'asd fadf asdf asdf asdf asdfasdf asdf asdf',
        )
     *
     * @param array $p
     * @param int $key
     */
    private function paragraph(array $p, int $key) : void {
        $rules = [];
        $rules['label']         = 'required|max:1000';
        $v = $this->vf->make($p, $rules);
        $this->generateErrors($v, $key);
    }

    /**
     * Validate radio group field. Sample input:
     *
        array (
            'required' => true, // optional
            'label' => 'Radio Group',
            'inline' => true, // optional
            'values' => array (
                0 => array (
                    'label' => 'asdf',
                    'value' => 'asdf',
                    'selected' => true, // optional
                )
        )
     *
     * @param array $rg
     * @param int $key
     */
    private function radioGroup(array $rg, int $key) : void {
        $rules = [];
        $rules['label']         = 'required|max:100';
        $rules['required']      = isset($rg['required']) ? 'boolean' : '';
        $rules['inline']        = isset($rg['inline']) ? 'boolean' : '';
        $rules['values']        = ['required', function($attribute, $value, $fail){
            foreach($value as $v){
                if(!array_key_exists('label', $v) || $v['label'] === ''){
                    return $fail('Option must be present');
                }
                if(strlen($v['label']) > 100){
                    return $fail('Option must not exceed 100 characters');
                }
                if(array_key_exists('value', $v) && strlen($v['value']) > 100){
                    return $fail('Option value must not exceed 100 characters');
                }
                if(array_key_exists('selected', $v) && $v['selected'] !== true){
                    return $fail('Selected value must be true');
                }
            }
        }];
        $v = $this->vf->make($rg, $rules);
        $this->generateErrors($v, $key);
    }

    /**
     * Validate select field. Sample input:
     *
    array (
        'label'     => 'Select',
        'required'  => true, // optional
        'values'    => array (
            0 =>
            array (
                'label'     => '',
                'value'     => '',
                'selected'  => true // optional
            ),
        ),
    )
     *
     * @param array $s
     * @param int $key
     */
    private function select(array $s, int $key) : void {
        $rules = [];
        $rules['label']         = 'required|max:100';
        $rules['required']      = isset($s['required']) ? 'boolean' : '';
        $rules['values']        = ['required', function($attribute, $value, $fail){
            foreach($value as $v){
                if(!array_key_exists('label', $v) || $v['label'] === ''){
                    return $fail('Option must be present');
                }
                if(strlen($v['label']) > 100){
                    return $fail('Option must not exceed 100 characters');
                }
                if(array_key_exists('value', $v) && strlen($v['value']) > 100){
                    return $fail('Option value must not exceed 100 characters');
                }
                if(array_key_exists('selected', $v) && $v['selected'] !== true){
                    return $fail('Selected value must be true');
                }
            }
        }];
        $v = $this->vf->make($s, $rules);
        $this->generateErrors($v, $key);
    }

    /**
     * Validate text area field. Sample input:
     *
     * array (
        'required' => true, // optional
        'label' => 'Text Area',
        'maxlength' => '1', // optional
        'rows' => '1', // optional
    )
     *
     *
     * @param array $ta
     * @param int $key
     */
    private function textArea(array $ta, int $key) : void {
        $rules = [];
        $rules['label']         = 'required|max:100';
        $rules['required']      = isset($ta['required']) ? 'boolean' : '';
        $rules['maxlength']     = isset($ta['maxlength']) ? 'integer' : '';
        $rules['rows']          = isset($ta['rows']) ? 'integer' : '';
        $v = $this->vf->make($ta, $rules);
        $this->generateErrors($v, $key);
    }

    /**
     * Validate text field. Sample input:
     *
     * array (
     * 'required' => true, // optional
     * 'label' => 'Text Field',
     * 'maxlength' => '3', // optional
     * )
     *
     * @param array $tf
     * @param int $key
     */
    private function text(array $tf, int $key) : void {
        $rules = [];
        $rules['label']         = 'required|max:100';
        $rules['required']      = isset($tf['required']) ? 'boolean' : '';
        $rules['maxlength']     = isset($tf['maxlength']) ? 'integer' : '';
        $v = $this->vf->make($tf, $rules);
        $this->generateErrors($v, $key);
    }

    /**
     * If errors exist, add them to the $errors property
     *
     * @param Validator $v
     * @param int $key
     */
    private function generateErrors(Validator &$v, int &$key) : void {
        if($v->fails()){
            foreach($v->errors()->getMessages() as $e){
                if(is_array($e)){
                    foreach($e as $ee){
                        $this->errors[] = 'Form field ' . ($key + 1) . ': ' . $ee;
                    }
                } else {
                    $this->errors[] = 'Form field ' . ($key + 1) . ': ' . $e;
                }
            }
        }
    }

}