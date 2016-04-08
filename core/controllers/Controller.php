<?php
class Controller {
     
    protected $_model;
    protected $_controller;
    protected $_action;
    protected $_template;
    protected $autoRender = true;
 
    function __construct($model, $controller, $action) {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_model = $model;
 
        $this->$model = new $model;
        $this->_template = new View($model,$controller,$action);
    }
 
    function set($name,$value) {
        $this->_template->set($name,$value);
    }
 
    function __destruct() {
        if($this->autoRender)
            $this->_template->render();
    }
         
}