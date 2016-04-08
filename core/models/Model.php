<?php
class Model extends SQLQuery {
 
    function __construct() {
        parent::__construct();
        $this->beforeFilter();
        $this->_model = get_class($this);
        $this->_table = strtolower($this->_model)."s";
    }

    protected function beforeFilter(){

    }
    
    protected function beforeSave($data){
        return $data;
    }
    
    protected function afterSave($data){
        return $data;
    }

    function save($data){
        $new_data = $this->beforeSave($data);
        return $this->afterSave($this->doSave($new_data));
    }    

    protected function beforeDelete($id){
        return $data;
    }
    
    protected function afterDelete($data){
        return $data;
    }

    function delete($id){
        $this->beforeDelete($id);
        return $this->afterDelete($this->doDelete($id));
    }
}