<?php

namespace SkayaPHP\Core\Components;

class SessionComponent extends Component{
	
    public function init(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Write a value on the current session
     * @param string $key
     * @param mixed $value
     */
    public function write($key, $value){
        $_SESSION[$key] = $value;
    }

    /**
     * Read a value from the current session
     * @param string $key
     * @return mixed Value if found, null if not
     */
    public function read($key){
        if(isset($_SESSION[$key]))
            return $_SESSION[$key];
        else
            return null;
    }
}