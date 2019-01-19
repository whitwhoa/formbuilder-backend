<?php

namespace whitwhoa\FormBuilderBackend;

use Illuminate\Validation\Validator;
use JeffOchoa\ValidatorFactory;

class ValidateFormBuilder {

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
            switch($f['type']){
                case 'text':
                    $this->textField($f, $k);
                    break;
                default:
                    $this->errors[] = 'Form field ' . ($k + 1) . ': Unsupported form type';
            }
        }
    }

    /**
     * Validate text field. Sample input:
     *
     * array (
     * 'required' => true, // may or may not be present
     * 'label' => 'Text Field',
     * 'maxlength' => '3', // may or may not be present
     * )
     *
     * @param array $tf
     * @param int $key
     */
    private function textField(array $tf, int $key) : void {
        $rules = [];
        $rules['label']         = 'required|max:255';
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