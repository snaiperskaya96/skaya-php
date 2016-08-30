<?php

namespace SkayaPHP\Factories;

class Factory {
    protected static $instance;

    protected function __construct() {
        return;
    }

    /**
     *
     * @return static
     */
    protected static function getInstance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }
    
}
