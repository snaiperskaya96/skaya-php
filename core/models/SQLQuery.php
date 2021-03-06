<?php

namespace SkayaPHP\Models;

class SQLQuery {
    private $_db;
    protected $_model;
    protected $_table;
    protected $belongsTo = [];
    

    public function __construct() {
        try{
            if(DB_HOST != "" && DB_NAME != "" && DB_USER != "" && DB_PASSWORD != "")
                $this->_db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
        } catch (PDOException $e){
            if(DEV_MODE)
                $_SESSION['errors'][] = $e->getMessage();
        }
    }
    
    public function __destruct() {
    }
    
    public function getLastInsertId(){
        return $this->_db->lastInsertId();
    }
    
    public function getAll($conditions = "",$limit = ""){
        $query = "SELECT * FROM " . $this->_table;
        if($conditions != "")
            $query .= " WHERE " .$conditions;
        if($limit != "")
            $query .= " LIMIT " .$limit;
        $statement = $this->_db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($results)){
            $return = [];
            foreach($results as $r){
                $returnArr = [$this->_model => $r];
                if(!empty($this->belongsTo)){
                    foreach($this->belongsTo as $bt){
                        if(!is_array($bt)){
                            $mod = new $bt;
                            $res = $mod->getFirst("id = '".$r[strtolower($bt).'_id'] ."'");
                            if(!empty($res)){
                                $returnArr[$bt] = $res[$bt];
                            }
                        } else {
                            $modName = array_keys($bt)[0];
                            $mod = new $modName;
                            $res = $mod->getFirst("id = '".$r[$bt['foreignKey']] ."'");
                            if(!empty($res)){
                                $returnArr[$bt] = $res[$bt];
                            }
                        }
                    }
                }
                $return[] = $returnArr;
            }
            return $return;
        } else return null;
    }
    
    public function getFirst($conditions = ""){
        $query = "SELECT * FROM " . $this->_table;
        if($conditions != "")
            $query .= " WHERE " .$conditions;
        $query .= " LIMIT 0,1";
        $statement = $this->_db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            $return = [$this->_model => $result[0]];
            $res = $result[0];
            $returnArr = [$this->_model => $res];
            if(!empty($this->belongsTo)){
                foreach($this->belongsTo as $bt){
                    if(!is_array($bt)){
                        $mod = new $bt;
                        $res = $mod->getFirst("id = '".$result[0][strtolower($bt).'_id'] ."'");
                        if(!empty($res)){
                            $returnArr[$bt] = $res[$bt];
                        }
                    } else {
                        $modName = array_keys($bt)[0];
                        $mod = new $modName;
                        $res = $mod->getFirst("id = '".$result[0][$bt['foreignKey']] ."'");
                        if(!empty($res)){
                            $returnArr[$bt] = $res[$bt];
                        }
                    }
                }
                $return = $returnArr;
            }
            return $return;
        }
        else return null;
    }
    
    public function getById($id = null){
        if($id == null)
            return [];
        $query = "SELECT * FROM " . $this->_table ." WHERE id = " .intval($id);
        $statement = $this->_db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            $res = $result[0];
            $return = [];
            $returnArr [$this->_model] = $res;
            if(!empty($this->belongsTo)){
                foreach($this->belongsTo as $bt){
                    if(!is_array($bt)){
                        $mod = new $bt;
                        $res = $mod->getFirst("id = '".$result[0][strtolower($bt).'_id'] ."'");
                        if(!empty($res)){
                            $returnArr[$bt] = $res[$bt];
                        }
                    } else {
                        $modName = array_keys($bt)[0];
                        $mod = new $modName;
                        $res = $mod->getFirst("id = '".$result[0][$bt['foreignKey']] ."'");
                        if(!empty($res)){
                            $returnArr[$bt] = $res[$bt];
                        }
                    }
                }
                $return = $returnArr;
            }
            return $return;
            
        }
        else return null;
    }
    
    protected function doSave($data){
        $query = "INSERT INTO $this->_table (";
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

    protected function doDelete($id){
        $id = intval($id);
        $query = "DELETE FROM $this->_table WHERE id=$id";
        $res = $this->_db->query($query);
        return $res;
    }

    public function doUpdate($data){
        $id = intval($data['id']);
        unset($data['id']);
        if($id != null){
            $first = true;
            $query = "UPDATE $this->_table SET";
            foreach($data as $k => $v){
                if($first){
                    $query .= " $k='$v'";
                    $first = false;
                } else {
                    $query .= ", $k='$v'";                    
                }
            }
            $query .= " WHERE id=$id";
            $res = $this->_db->query($query);
            return $res;
        }
    }
}