<?php
namespace App\Core;

/**
 * @package App\Core
 */
class Request{
    /**
     * Give the url
     * @return string
     */
    public function getPath(){
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path,'?');
        if(!$position){
            return $path;
        }
        return substr($path,0,$position);
    }
    /**
     * Give the method
     * @return string
     */
    public function getMethod(){
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    /**
     * Give the post/get array body
     * @return array
     */
    public function getBody(){
        $body = [];
        switch($this->getMethod()){
            case 'post':
                $type = $_POST;
                $input = INPUT_POST;
                break;
            default:
                $type = $_GET;
                $input = INPUT_GET;
                break;                
        }
        foreach($type as $k => $val){
            $body[$k] = filter_input($input,$k,FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $body;
    }
}