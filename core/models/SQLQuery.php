<?php
class SQLQuery {
    private $_db;
    protected $_model;
    protected $_table;

    public function __construct() {
        try{
            $this->_db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
        } catch (PDOException $e){
            if(DEV_MODE)
                die($e->getMessage());
        }
    }
    
    public function __destruct() {
    }
    
    public function getLastInsertId(){
        return $_db->lastInsertId();
    }
    
    public function getAll($conditions = "",$limit = ""){
        $query = "SELECT * FROM " . $this->_table;
        if($conditions != "")
            $query .= " WHERE " .$conditions;
        if($limit != "")
            $query .= " LIMIT " .$limit;
        $statment = $this->_db->prepare($query);            
        $statment->execute();
        $results = $statment->fetchAll(PDO::FETCH_CLASS, $this->_model);
        return $results;
    }
    
    public function getFirst($conditions = ""){
        $query = "SELECT * FROM " . $this->_table;
        if($conditions != "")
            $query .= " WHERE " .$conditions;
        $query .= " LIMIT 0,1";
        $statment = $this->_db->prepare($query);            
        $statment->execute();
        $result = $statment->fetchAll(PDO::FETCH_CLASS, $this->_model);
        return $result;
    }
    
    public function getById($id = null){
        if($id == null)
            return [];
        $query = "SELECT * FROM " . $this->_table ." WHERE id = " .intval($id);
        $statment = $this->_db->prepare($query);            
        $statment->execute();
        $result = $statment->fetchAll(PDO::FETCH_CLASS, $this->_model);
        return $result;        
    }
    
    protected function doSave($data){
        $query = "INSERT INTO " .$this->_table . " (";
        $first = true;
        foreach(array_keys($data) as $key){
            if(!$first)
                $query .= "," . $key;
            else{
                $query .= $key;
                $first = false;
            }
        }
        $query .= ") VALUES (";
        $first = true;
        foreach($data as $v){
            if(!$first)
                $query .= ",'" .$v ."'";
            else{
                $query .= "'" .$v ."'";
                $first = false;
            }
        }
        $query .= ");";
        $res = $this->_db->query($query);
        return $res;
    }
}