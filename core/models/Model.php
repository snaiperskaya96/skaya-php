<?php

namespace SkayaPHP\Core\Models;

class Model extends SQLQuery {
 
    function __construct() {
        parent::__construct();
        $this->beforeFilter();
        $this->_model = get_class($this);
        $this->_table = strtolower($this->_model)."s";
    }

    /**
     * The beforeFilter function get executed right after the construct
     */
    protected function beforeFilter(){

    }

    /**
     * Handles the $data before the save() and update() functions
     * @param mixed $data to be saved
     * @return mixed
     */
    protected function beforeSave($data){
        return $data;
    }

    /**
     * Handles the $data after the save() and update() functions
     * @param mixed $data saved
     * @return mixed
     */
    protected function afterSave($data){
        return $data;
    }

    /**
     * Save a model
     * @param $data array Named array with the field => value format
     * @return mixed
     */
    function save($data){
        $new_data = $this->beforeSave($data);
        return $this->afterSave($this->doSave($new_data));
    }

    /**
     * Handles the $data before the delete() function
     * @param mixed $id to be saved
     * @return mixed
     */
    protected function beforeDelete($id){
        return $id;
    }

    /**
     * Handles the $data after the delete() function
     * @param mixed $data
     * @return mixed
     */
    protected function afterDelete($data){
        return $data;
    }

    /**
     * Delete a model based on its id
     * @param $id
     * @return mixed
     */
    function delete($id){
        $this->beforeDelete($id);
        return $this->afterDelete($this->doDelete($id));
    }

    /**
     * Update an object based on its id
     * @param array $data Named array with the field => value format
     * @return mixed
     */
    function update($data){
        $nData = $this->beforeSave($data);
        return $this->afterSave($this->doUpdate($nData));
    }
}