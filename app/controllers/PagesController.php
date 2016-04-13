<?php
class PagesController extends AppController {
    protected $acp = [
        ACP_ALLOW_EVERYONE,
        'Allow' => ['home' => []],
        'Deny' => ['home' => [],'secretPage' => ACP_DENY_EVERYONE]
    ];

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

    function secretPage(){
        $this->autoRender = false;
        echo "No one knows!";
    }
}