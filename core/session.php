<?php
namespace App\Core;

class Session{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct(){
        session_start();
        $msgs = $_SESSION[self::FLASH_KEY] ?? [];
        foreach($msgs as $k => $flash){
            $msgs[$k]['removed'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $msgs;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function set(string $key, string $value){
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return string
     */
    public function get(string $key){
        return $_SESSION[$key] ?? false;
    }

    /**
     * @param string $key
     * @return string
     */
    public function remove(string $key){
        unset($_SESSION[$key]);
    }

    /**
     * @param string $key
     * @param string $message
     * @return void
     */
    public function setFlash(string $key, string $message){
        $_SESSION[self::FLASH_KEY][$key] = [
            'removed' => false,
            'value' => $message
        ];
    }

    /**
     * @param string $key
     * @param string $message
     * @return string
     */
    public function getFlash(string $key){
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function __destruct(){
        $msgs = $_SESSION[self::FLASH_KEY] ?? [];
        foreach($msgs as $k => $flash){
            if($flash['removed']){
                unset($msgs[$k]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $msgs;
    }
}