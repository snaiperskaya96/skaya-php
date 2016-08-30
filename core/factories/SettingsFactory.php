<?php

namespace SkayaPHP\Core\Factories;

class SettingsFactory extends Factory{

    private $settings = [];
    private $authSettings = [];

    protected function __construct() {
        parent::__construct();
        $default = include ROOT . DS . 'library' . DS . 'default.php';
        
        if(file_exists(ROOT . DS . 'config' . DS . 'config.php')) {
            $this->settings = include ROOT . DS . 'config' . DS . 'config.php';
        } else {
            $this->settings = $default['settings'];
        }
        
        if(file_exists(ROOT . DS . 'config' . DS . 'auth.config.php')) {
            $this->authSettings = include ROOT . DS . 'config' . DS . 'auth.config.php';
        } else {
            $this->authSettings = $default['auth'];
        }

    }

    public static function getAll($fromAuth = false) {
        $instance = self::getInstance();
        if (!$fromAuth) {
            return $instance->settings;
        } else {
            return $instance->authSettings;
        }
    }

    public static function get($key, $fromAuth = false) {
        $instance = $this->getInstance();
        if (!$fromAuth) {
            return $instance->settings[$key];
        } else {
            return $instance->authSettings[$key];
        }
    }

}
