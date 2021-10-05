<?php

use App\Core\Controller;

if(!function_exists('vardump')){
    function vardump($var){
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        exit;
    }
}

if(!function_exists('printr')){
    function printr($var){
        echo '<pre>';
        print_r($var);
        echo '</pre>';
        exit;
    }
}

if(!function_exists('view')){
    function view($view,$params = []){ 
        return (new Controller())->render($view,$params);
    }
}

if(!function_exists('redirect')){
    function redirect($path){
        header('location: '.$path);
    }
}