<?php
namespace App\Core;

/**
 * @package App\Core
 */
class Response{
    /**
     * Response code HTTP
     * @param int $code
     */
    public function status(int $code){
        http_response_code($code);
    }
}