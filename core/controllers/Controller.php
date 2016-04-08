<?php
class Controller {
     
    protected $_model;
    protected $_controller;
    protected $_action;
    protected $_template;
    protected $title = DEFAULT_TITLE;
    protected $layout = DEFAULT_LAYOUT;
    protected $autoRender = true;
    protected $helpers = ['Html','Css','Js'];
    protected $components = [];
 
    function __construct($model, $controller, $action) {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_model = $model;

        foreach($this->components as $comp){
            $componentClass = $comp."Component";
            $this->$comp = new $componentClass();
        }

        $this->$model = new $model;
        $this->_template = new View($model,$controller,$action,$this->layout,$this->title);

    }
 
    function set($name,$value) {
        $this->_template->set($name,$value);
    }
 
    function __destruct() {
        if($this->autoRender){
            if(!empty($this->helpers)){
                $this->_template->setHelpers($this->helpers);
            }
            $this->_template->render();
        }
    }

    function loadModel($modelName) {
        $this->$modelName = new $modelName();
    }
         
}