<?php
class AuthComponent extends Component{ 

    public function init(){
        require_once(ROOTPATH . DS . 'core' . DS . 'vendor' . DS . 'password_lib.php');
        require_once (ROOTPATH . DS . 'config' . DS . 'auth.config.php');

        // Make it indipendent from SessionComponent
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function hash($password){
        return password_hash(AUTH_CONFIG['salt'].$password, PASSWORD_BCRYPT, ['cost' => AUTH_CONFIG['cpu_cost']]);
    }
    
    public function authenticate($username,$password,$otherFields = []){
        $modelName = AUTH_CONFIG['model'];
        $model = new $modelName;
        $where = AUTH_CONFIG['username_column'] . "='$username'";
        foreach($otherFields as $k=>$f){
            $where .= " AND $k='$f'";
        }
        $user = $model->getFirst($where);
        if(!empty($user)){
            $passField = AUTH_CONFIG['password_column'];
            if(password_verify(AUTH_CONFIG['salt'].$password, $user[$passField])){
                unset($user[$passField]);
                $_SESSION['Auth'] = $user;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        return false;
    }
    
    public function getUser(){
        if(isset($_SESSION['Auth']))
            return $_SESSION['Auth'];
        else return false;
    }
}