<?php
class View {
     
    protected $variables = [];
    protected $_pageTitle = "";
    protected $_model;
    protected $_controller;
    protected $_action;
    protected $_layout = DEFAULT_LAYOUT;
    private $_helpers = [];

    function __construct($model,$controller,$action,$layout = "default",$title = "") {
        $this->_model = $model;
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_pageTitle = $title;
        $this->_layout = $layout;
    }
 
 
    function set($name,$value) {
        $this->variables[$name] = $value;
    }
 
    function setHelpers($helpers = []){
        $this->_helpers = $helpers;
    }
    
    function setComponents($components = []){
        foreach($components as $component){
            $compName = $component.'Component';
            $this->$component = new $compName;
        }
    }

    function render() {
        foreach($this->_helpers as $h){
            $helperName = $h."Helper";
            $this->$h = new $helperName();
        }
        extract($this->variables);
        if($this->_layout != "" && $this->_layout != false && $this->_layout != null){
            if(file_exists(ROOT . DS . 'app' . DS . 'views' . DS . 'layout' . DS . $this->_layout . '.phtml')){
                include (ROOT . DS . 'app' . DS . 'views' . DS . 'layout' . DS . $this->_layout . '.phtml');
            } else {
                $_SESSION['errors'][] = "Layout $this->_layout not found.";
            }
        }
    }
    
    function content(){
        extract($this->variables);
        if(file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.phtml')){
            include (ROOT . DS . 'app' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.phtml');
        } else {
            $_SESSION['errors'][] = "Cannot find a view for $this->_action";
        }
    }
    
    function pushElement($elementName){
        
        if(file_exists(ROOT . DS . 'app' . DS . 'views' . DS . 'elements' . DS . $elementName . '.phtml')){
            include (ROOT . DS . 'app' . DS . 'views' . DS . 'elements' . DS . $elementName . '.phtml');
        } else {
            $_SESSION['errors'][] = "Cannot find element $elementName";
        }
    }
 
}