<?php
namespace App\Core\Exception;

use Exception;

class ForbidenException extends Exception{
    /** @var int */
    protected $code = 403;
    /** @var string */
    protected $message = 'You don\'t have permission to access this page';

}