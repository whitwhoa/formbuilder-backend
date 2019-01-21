<?php

namespace whitwhoa\FormBuilderBackend;

class RenderFormElements {

    private $renderType;
    private $formData;
    private $renderedHtml;
    private $formId;
    private $old;
    private $errors;

    /**
     * RenderForm constructor.
     *
     * @param string $formData
     * @param array $oldData
     * @param array $errors
     * @param string $renderType
     * @throws \Exception
     */
    public function __construct(string $formData, array $oldData=[], array $errors=[], string $renderType='bootstrap41')
    {
        $this->formData = json_decode($formData);
        $this->old = $oldData;
        $this->errors = $errors;
        $this->setFormIdFromData();
        $this->renderType = $renderType;
        switch($this->renderType){
            case 'bootstrap41':
                $this->renderBootstrap41($formData);
                break;
            default:
                throw new \Exception('Unsupported render type');
        }
    }

    /**
     * Obtain rendered html
     *
     * @return string
     */
    public function getRenderedHtml() : string {
        return $this->renderedHtml;
    }

    /**
     * Set parameter formId equal to the id present in the meta object of property formData
     */
    private function setFormIdFromData() : void {
        $this->formId = ((function(){
            foreach($this->formData as $e){
                if($e->type === 'meta'){
                    return $e->data->id;
                }
            }
        })());
    }

    /**
     * Render the form elements using bootstrap 4.1
     *
     */
    private function renderBootstrap41() : void {
        $this->renderedHtml = ((function(){
            ob_start();
            include __DIR__.'/templates/bootstrap41.php';
            return ob_get_clean();
        })());
    }

}