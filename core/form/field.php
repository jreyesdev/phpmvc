<?php
namespace App\Core\Form;

use App\Core\Model;

class Field{
    /** @var Model */
    public $model;

    /** @var string */
    public $attr;

    /** @var string */
    public $type;

    public function __construct(Model $model, string $attr, string $type){
        $this->model = $model;
        $this->attr = $attr;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function __toString(){
        return sprintf('
            <label for="%s">%s</label>
            <input type="%s" name="%s" value="%s" class=" %s">
            <div><span>%s</span></div>
        ',
            strtolower($this->attr),
            $this->label($this->attr),
            strtolower($this->type),
            strtolower($this->attr),
            $this->model->{$this->attr},
            $this->model->hasError($this->attr) ? 'is-invalid' : '',
            $this->model->getFirstError($this->attr)
        );
    }

    /** 
     * @param string $name
     * @return string
    */
    protected function label(string $name){
        return ucwords(strtolower($this->model->getLabel($name)));
    }
}