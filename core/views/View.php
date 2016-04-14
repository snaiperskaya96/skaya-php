<?php
class View {
     
    protected $variables = [];
    protected $_pageTitle = '';
    protected $_model;
    protected $_controller;
    protected $_action;
    protected $isPlugin = false;
    protected $pluginName = '';
    protected $_layout = DEFAULT_LAYOUT;
    private $_helpers = [];

    function __construct($model,$controller,$action,$layout = "default",$title = "") {
        $this->_model = $model;
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_pageTitle = $title;
        $this->_layout = $layout;
    }

    function setLayout($layout = false){
        $this->_layout = $layout;
    }
    /**
     * Makes $value available in the view by calling $name
     * @param string $name
     * @param mixed $value
     */
    function set($name,$value) {
        $this->variables[$name] = $value;
    }

    /**
     * Makes $helpers available inside the view
     * @param array $helpers
     */
    function setHelpers($helpers = []){
        $this->_helpers = $helpers;
    }

    /**
     * Makes $components available inside the view
     * @param array $components
     */
    function setComponents($components = []){
        foreach($components as $component){
            $compName = $component.'Component';
            $this->$component = new $compName($this);
        }
    }

    /**
     * Render the layout which will render the view
     */
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

    /**
     * Render the view - Called from the layout - Usually
     */
    function content(){
        extract($this->variables);
        if(!$this->isPlugin) {
            if (file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.phtml')) {
                include(ROOT . DS . 'app' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.phtml');
            } else {
                $_SESSION['errors'][] = "Cannot find a view for $this->_action";
            }
        } else {
            if (file_exists(ROOT . DS . 'app' . DS . 'plugins' . DS . $this->pluginName . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.phtml')) {
                include(ROOT . DS . 'app' . DS . 'plugins' . DS . $this->pluginName . DS .'views' . DS . $this->_controller . DS . $this->_action . '.phtml');
            } else {
                $_SESSION['errors'][] = "Cannot find a view for $this->_action";
            }

        }
    }

    /**
     * Render an element inside a view
     * @param string $elementName
     */
    function pushElement($elementName){
        
        if(file_exists(ROOT . DS . 'app' . DS . 'views' . DS . 'elements' . DS . $elementName . '.phtml')){
            include (ROOT . DS . 'app' . DS . 'views' . DS . 'elements' . DS . $elementName . '.phtml');
        } else {
            $_SESSION['errors'][] = "Cannot find element $elementName";
        }
    }

    function basePath(){
        return BASEPATH;
    }

    public function setIsPlugin($isPlugin,$pluginName = ""){
        $this->isPlugin = $isPlugin;
        $this->pluginName = $pluginName;
    }
 
}