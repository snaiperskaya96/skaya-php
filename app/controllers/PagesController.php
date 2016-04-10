<?php
class PagesController extends AppController {    
    function home(){
        $db = false;
        try{
            if(DB_HOST != "" && DB_NAME != "" && DB_USER != "" && DB_PASSWORD != ""){
                $this->_db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
                $db = true;
            }
        } catch (PDOException $e){
        }
        $this->set('dbconnection',$db);
        $this->set('writabletmp',is_writable(ROOTPATH.'/tmp'));
    }
}