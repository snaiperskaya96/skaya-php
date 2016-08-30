<?php

namespace SkayaPHP\Controllers;

use SkayaPHP\Models\Request;
use SkayaPHP\Views\View;

class Controller {
     
    protected $_model;
    protected $_controller;
    protected $_action;
    protected $_template;
    protected $title;
    protected $layout;
    protected $isPlugin = false;
    protected $pluginName = "";
    protected $autoRender = true;
    protected $helpers = ['Html','Css','Js'];
    protected $components = [];
    protected $request;
    protected $acp = [];
    protected $settings;
    protected $acpSettings;

 
    function __construct($model, $controller, $action) {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_model = $model;

        $this->settings = \SkayaPHP\Factories\SettingsFactory::getAll();
        $this->acpSettings = \SkayaPHP\Factories\SettingsFactory::getAll(true);

        $this->acp = [$this->acpSettings['ACP_ALLOW_EVERYONE'], 'Allow' => [], 'Deny' => ''];

        $this->title = $this->settings['DEFAULT_TITLE'];
        $this->layout = $this->settings['DEFAULT_LAYOUT'];
        
        $this->request = new Request();
        
        foreach($this->components as $comp){
            $componentClass = $comp."Component";
            if(!class_exists($componentClass))
                $componentClass = 'SkayaPHP\\Components\\' . $componentClass;
            $this->$comp = new $componentClass($this);
        }
        
        if($model != "" && class_exists($model))
            $this->$model =  new $model;

        $this->_template = new View($model,$controller,$action,$this->layout,$this->title);
        $this->_template->setComponents($this->components);
        $this->_template->setIsPlugin($this->isPlugin,$this->pluginName);
    }

    function setLayout($layout = false){
        $this->_template->setLayout($layout);
    }

    /**
     * Makes $value available in the view by calling $name
     * @param string $name
     * @param mixed $value
     */
    function set($name,$value) {
        $this->_template->set($name,$value);
    }
 
    function __destruct() {
        if($this->autoRender){
            if(!empty($this->helpers) && $this->_template != null){
                $this->_template->setHelpers($this->helpers);
            }
            if($this->_template != null)
                $this->_template->render();
        }
    }

    /**
     * Include the model $modelName (callable via $this->$modelName)
     * @param string $modelName
     */
    function loadModel($modelName) {
        $this->$modelName = new $modelName();
    }

    /**
     * Redirect to the given controller->action
     * @param $controller
     * @param string $action default is index
     * @param array $arguments
     */
    function redirect($controller, $action = "index", $arguments = []){
        $url = BASEPATH."$controller/$action/";
        foreach($arguments as $arg)
            $url .= "$arg/";
        header("Location: $url");
    }

    /**
     * @return array
     */
    public function getAcp()
    {
        return $this->acp;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->_action;
    }
}