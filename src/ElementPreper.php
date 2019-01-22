<?php

namespace whitwhoa\FormBuilderBackend;

use Ramsey\Uuid\Uuid;


class ElementPreper {

    private $fields;
    private $defaultTextMaxLength = 100;
    private $defaultTextAreaMaxLength = 1000;
    private $meta;

    public function __construct(string $jsonString='', array $meta=[])
    {
        if($jsonString !== ''){
            $this->setFields($jsonString, $meta);
        }
    }

    public function setFields(string $jsonString, array $meta=[]) : void {
        $this->fields = json_decode($jsonString);
        $this->meta = $meta;
        $this->prepareFields();
    }

    /**
     * return $fields property
     *
     * @return array
     */
    public function getFields() : array {
        return $this->fields;
    }

    /**
     * return json encoded $fields property
     *
     * @return string
     */
    public function getFieldsAsJSON() : string {
        return json_encode($this->fields);
    }

    /**
     * For each field type:
     *
     *  > generate a "name" to be used within html form
     *  > create a validation string to be used when form is submitted
     *
     */
    private function prepareFields() : void {
        foreach($this->fields as $k => $f){
            switch($f->type){
                case 'text':
                    $this->prepText($k);
                    break;
                case 'textarea':
                    $this->prepTextArea($k);
                    break;
                case 'select':
                    $this->prepSelect($k);
                    break;
                case 'radio-group':
                    $this->prepRadioGroup($k);
                    break;
                case 'number':
                    $this->prepNumber($k);
                break;
                case 'date':
                    $this->prepDate($k);
                    break;
                case 'checkbox-group':
                    $this->prepCheckboxGroup($k);
                    break;
            }
        }
        $this->fields[] = (object)[
            'type' => 'meta',
            'data' => (object)array_merge(['id' => Uuid::uuid4()->toString()], $this->meta)
        ];
    }

    /**
     * Prepare checkbox-group field
     *
     * @param int $key
     */
    private function prepCheckboxGroup(int $key) : void {
        $this->fields[$key]->name = 'checkbox-group_' . uniqid();
        $this->fields[$key]->validation = implode('|', (function() use($key){
            $a = [];
            if(isset($this->fields[$key]->required) && $this->fields[$key]->required === true){
                $a[] = 'required';
            } else {
                $a[] = 'sometimes';
            }
            $a[] = 'in:' . ((function() use($key){
                    $allowed = [];
                    foreach($this->fields[$key]->values as $v){
                        $allowed[] = '"' . ($v->value !== '' ? $v->value : $v->label) . '"';
                    }
                    return implode(',', $allowed);
                })());
            return $a;
        })());
    }

    /**
     * Prepare date field
     *
     * @param int $key
     */
    private function prepDate(int $key) : void {
        $this->fields[$key]->name = 'date_' . uniqid();
        $this->fields[$key]->validation = implode('|', (function() use($key){
            $a = [];
            if(isset($this->fields[$key]->required) && $this->fields[$key]->required === true){
                $a[] = 'required';
            } else {
                $a[] = 'sometimes';
            }
            $a[] = 'date';
            return $a;
        })());
    }

    /**
     * Prepare number field
     *
     * @param int $key
     */
    private function prepNumber(int $key) : void {
        $this->fields[$key]->name = 'number_' . uniqid();
        $this->fields[$key]->validation = implode('|', (function() use($key){
            $a = [];
            if(isset($this->fields[$key]->required) && $this->fields[$key]->required === true){
                $a[] = 'required';
            } else {
                $a[] = 'sometimes';
            }
            $a[] = 'integer';
            if(isset($this->fields[$key]->min)){
                $a[] = 'min:' . $this->fields[$key]->min;
            }
            if(isset($this->fields[$key]->max)){
                $a[] = 'max:' . $this->fields[$key]->max;
            }
            return $a;
        })());
    }

    /**
     * Prepare radio group field
     *
     * @param int $key
     */
    private function prepRadioGroup(int $key) : void {
        $this->fields[$key]->name = 'radio-group_' . uniqid();
        $this->fields[$key]->validation = implode('|', (function() use($key){
            $a = [];
            if(isset($this->fields[$key]->required) && $this->fields[$key]->required === true){
                $a[] = 'required';
            } else {
                $a[] = 'sometimes';
            }
            $a[] = 'in:' . ((function() use($key){
                    $allowed = [];
                    foreach($this->fields[$key]->values as $v){
                        $allowed[] = '"' . ($v->value !== '' ? $v->value : $v->label) . '"';
                    }
                    return implode(',', $allowed);
                })());
            return $a;
        })());
    }

    /**
     * Prepare select field
     *
     * @param int $key
     */
    private function prepSelect(int $key) : void {
        $this->fields[$key]->name = 'select_' . uniqid();
        $this->fields[$key]->validation = implode('|', (function() use($key){
            $a = [];
            if(isset($this->fields[$key]->required) && $this->fields[$key]->required === true){
                $a[] = 'required';
            } else {
                $a[] = 'sometimes';
            }
            $a[] = 'in:' . ((function() use($key){
                $allowed = [];
                foreach($this->fields[$key]->values as $v){
                    $allowed[] = '"' . ($v->value !== '' ? $v->value : $v->label) . '"';
                }
                return implode(',', $allowed);
            })());
            return $a;
        })());
    }

    /**
     * Prepare textarea field
     *
     * @param int $key
     */
    private function prepTextArea(int $key) : void {
        $this->fields[$key]->name = 'textarea_' . uniqid();
        $this->fields[$key]->validation = implode('|', (function() use($key){
            $a = [];
            if(isset($this->fields[$key]->required) && $this->fields[$key]->required === true){
                $a[] = 'required';
            } else {
                $a[] = 'sometimes';
            }
            if(isset($this->fields[$key]->maxlength)){
                $a[] = 'max:' . $this->fields[$key]->maxlength;
            } else {
                $a[] = 'max:' . $this->defaultTextAreaMaxLength;
            }
            return $a;
        })());
    }

    /**
     * Prepare text input field
     *
     * @param int $key
     */
    private function prepText(int $key) : void {
        $this->fields[$key]->name = 'text_' . uniqid();
        $this->fields[$key]->validation = implode('|', (function() use($key){
            $a = [];
            if(isset($this->fields[$key]->required) && $this->fields[$key]->required === true){
                $a[] = 'required';
            } else {
                $a[] = 'sometimes';
            }
            if(isset($this->fields[$key]->maxlength)){
                $a[] = 'max:' . $this->fields[$key]->maxlength;
            } else {
                $a[] = 'max:' . $this->defaultTextMaxLength;
            }
            return $a;
        })());
    }

}