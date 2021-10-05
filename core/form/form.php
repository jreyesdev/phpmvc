<?php
namespace App\Core\Form;

use App\Core\Model;

class Form{
    /**
     * @param string $act
     * @param string $method
     */
    public static function begin(string $act, string $method){
        echo sprintf('<form action="%s" method="%s">',$act,$method);
        return new Form();
    }

    public static function end(){
        echo '</form>';
    }

    public function field(Model $model, string $attr, string $type = 'text'){
        return new Field($model,$attr,$type);
    }
}