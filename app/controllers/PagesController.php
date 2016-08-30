<?php
class PagesController extends AppController {

    public function __construct($model, $controller, $action) {
        parent::__construct($model, $controller, $action);
        $this->acp = [
            $this->acpSettings['ACP_ALLOW_EVERYONE'],
            'Allow' => ['home' => []],
            'Deny' => ['home' => [],'secretPage' => $this->acpSettings['ACP_DENY_EVERYONE']]
        ];

    }
    function home(){
        $db = false;
        $settings = SkayaPHP\Factories\SettingsFactory::getAll();
        try{
            if($settings['DB_HOST'] != "" && $settings['DB_NAME'] != "" && $settings['DB_USER'] != "" && $settings['DB_PASSWORD'] != ""){
                $this->_db = new PDO("mysql:host=".$settings['DB_HOST'].";dbname=".$settings['DB_NAME'], $settings['DB_USER'], $settings['DB_PASSWORD']);
                $db = true;
            }
        } catch (PDOException $e){
        }
        $this->set('dbconnection',$db);
        $this->set('writabletmp',is_writable($settings['ROOTPATH'].'/tmp'));
    }

    function secretPage(){
        $this->autoRender = false;
        echo "No one knows!";
    }
}