<?php
class View {
     
    protected $variables = array();
    protected $_model;
    protected $_controller;
    protected $_action;
    protected $_layout = "default";
     
    function __construct($model,$controller,$action) {
        $this->_model = $model;
        $this->_controller = $controller;
        $this->_action = $action;
    }
 
    /** Set Variables **/
 
    function set($name,$value) {
        $this->variables[$name] = $value;
    }
 
    /** Display Template **/
     
    function render() {
        extract($this->variables);
        if($this->_layout != "" && $this->_layout != false && $this->_layout != null){
            if(file_exists(ROOT . DS . 'app' . DS . 'views' . DS . 'layout' . DS . $this->_layout . '.php')){
                include (ROOT . DS . 'app' . DS . 'views' . DS . 'layout' . DS . $this->_layout . '.php');
            } else {
                die("Layout $this->_layout not found.");
            }
        }
    }
    function content(){ 
        if(file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $this->_model . DS . $this->_action . '.php'))
            include (ROOT . DS . 'app' . DS . 'views' . DS . $this->_model . DS . $this->_action . '.php');
    }
 
}