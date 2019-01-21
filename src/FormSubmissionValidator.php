<?php

namespace whitwhoa\FormBuilderBackend;

use Illuminate\Validation\Validator;
use JeffOchoa\ValidatorFactory;

class FormSubmissionValidator {

    private $vf;
    private $params;
    private $passes;

    private $errors = [];

    public function __construct(string $formJson, array $params)
    {
        $this->vf = new ValidatorFactory();
        $this->params = $params;
        $this->validate($formJson, $params);
        return $this;
    }

    public function passes() : bool {
        return $this->passes;
    }

    public function getErrors() : array {
        return $this->errors;
    }

    /**
     * Validate $params
     * @param string $formJson
     * @param array $params
     */
    private function validate(string $formJson, array $params) : void {
        $rules = [];
        foreach(json_decode($formJson) as $e){
            if(property_exists($e, 'name')){
                $rules[$e->name] = $e->validation;
            }
        }
        $v = $this->vf->make($params, $rules);
        $this->generateErrors($v);
    }

    private function generateErrors(Validator &$v) : void {
        $this->passes = !$v->fails();
        if(!$this->passes){
            foreach($v->errors()->getMessages() as $k => $e){
                $this->errors[$k] = is_array($e) ? $e[0] : $e;
            }
        }
    }


}
