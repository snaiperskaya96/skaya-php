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
    protected $request;
    protected $acp = [ACP_ALLOW_EVERYONE, // Applied to every action
        'Allow' => [], // ['actionName' => 1] (to role_id 1) or ['actionName' => [1,2,3]]
        'Deny' => []
    ];

 
    function __construct($model, $controller, $action) {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_model = $model;

        $this->request = new Request();
        
        foreach($this->components as $comp){
            $componentClass = $comp."Component";
            $this->$comp = new $componentClass($this);
        }
        
        if($model != "" && class_exists($model))
            $this->$model =  new $model;
        $this->_template = new View($model,$controller,$action,$this->layout,$this->title);
        $this->_template->setComponents($this->components);
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