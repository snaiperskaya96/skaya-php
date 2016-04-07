<?php
class Model extends SQLQuery {
    protected $_model;
 
    function __construct() {
        parent::__construct();
        //$this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $this->_model = get_class($this);
        $this->_table = strtolower($this->_model)."s";
    }
    
    protected function beforeSave($data){
        return $data;
    }
    
    protected function afterSave($data){
        return $data;
    }
    
    function save($data){
        $new_data = $this->beforeSave($data);
        $after_save_data = $this->doSave($new_data);
        return $this->afterSave($after_save_data);
    }
 

}