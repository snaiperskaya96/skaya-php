<?php
class AuthComponent extends Component{
    
    public function init(){
        require_once(ROOTPATH . DS . 'core' . DS . 'vendor' . DS . 'password_lib.php');

        // Make it indipendent from SessionComponent
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if(AUTH_CONFIG['acp_column'] != null && is_a($this->parent,"Controller")){
            if(!$this->checkAcp()){
                die("Unauthorized access");
            }
        }
    }

    public function checkAcp(){
        $me = ACP_GUEST;
        $generalRule = $this->parent->getAcp()[0];

        if($this->isAuthenticated())
            $me = $this->getValue(AUTH_CONFIG['acp_column']);

        $allowedRoles = isset($this->parent->getAcp()['Allow'][$this->parent->getAction()]) ? $this->parent->getAcp()['Allow'][$this->parent->getAction()] : [];
        $deniedRoles = isset($this->parent->getAcp()['Deny'][$this->parent->getAction()]) ? $this->parent->getAcp()['Deny'][$this->parent->getAction()] : [] ;

        if(!is_array($allowedRoles)) $allowedRoles = [$allowedRoles];
        if(!is_array($deniedRoles)) $deniedRoles = [$deniedRoles];

        $return = true;

        if($generalRule == ACP_DENY_EVERYONE)
            $return = false;

        if(empty($allowedRoles) && empty($deniedRoles)){
            if($generalRule == ACP_ALLOW_EVERYONE)
                $return = true;
            else if($generalRule != ACP_DENY_EVERYONE){
                if($generalRule == $me){
                    $return = true;
                } else $return = false;
            }
        }

        if((in_array($me,$allowedRoles) && !in_array($me,$deniedRoles)) ||
            (empty($allowedRoles) && !in_array($me,$deniedRoles) && !in_array(ACP_DENY_EVERYONE,$deniedRoles) && $generalRule != ACP_DENY_EVERYONE) ||
            (!in_array($me,$deniedRoles) && (in_array(ACP_ALLOW_EVERYONE,$allowedRoles) || $generalRule == ACP_ALLOW_EVERYONE)))
            $return = true;
        else $return = false;

        return $return;
    }
    
    /**
     * Hash a password using the salt provided into the config file
     * @param $password
     * @return string The hashed password
     */
    public function hash($password){
        return password_hash(AUTH_CONFIG['salt'].$password, PASSWORD_BCRYPT, ['cost' => AUTH_CONFIG['cpu_cost']]);
    }

    /**
     * Authenticate an user
     * @param string $username May be any unique field provided into the config file
     * @param string $password May be any field provided into the config file
     * @param array $otherFields Other fields to be checked (['country' => 'Italy'])
     * @return bool True if authenticated, False if not
     */
    public function authenticate($username, $password, $otherFields = []){
        $modelName = AUTH_CONFIG['model'];
        $model = new $modelName;
        $where = AUTH_CONFIG['username_column'] . "='$username'";
        foreach($otherFields as $k=>$f){
            $where .= " AND $k='$f'";
        }
        $user = $model->getFirst($where);
        if(!empty($user)){
            $passField = AUTH_CONFIG['password_column'];
            if(password_verify(AUTH_CONFIG['salt'].$password, $user[$modelName][$passField])){
                unset($user[$modelName][$passField]);
                $_SESSION['Auth'] = $user;
                session_id("user".$user[$modelName]['id']);
                session_regenerate_id();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        return false;
    }

    /**
     * De-authenticate the currently logged user
     */
    public function deauthenticate(){
        unset($_SESSION['Auth']);
        session_destroy();
    }

    /**
     * Get the current logged in user if any
     * @return mixed Logged user or false if not logged
     */
    public function getUser(){
        if(isset($_SESSION['Auth']))
            return $_SESSION['Auth'];
        else return false;
    }

    /**
     * Read a field from the authorized user
     * @param $value Name of the field
     * @return mixed
     */
    public function getValue($value){
        if($this->isAuthenticated()){
            $modelName = AUTH_CONFIG['model'];
            return $_SESSION['Auth'][$modelName][$value];
        } else return null;
    }

    /**
     * Check if the user is authenticated
     * @return bool
     */
    public function isAuthenticated(){
        if(isset($_SESSION['Auth']))
            return true;
        else return false;
    }

    /**
     * @param mixed $uniqueFieldValue Like user id
     * @param string $uniqueFieldName field to use with the $uniqueFieldValue, if none specified id will be used
     * @return bool True if logged, False if not
     */
    public function forceLogin($uniqueFieldValue, $uniqueFieldName = null){
        $field = $uniqueFieldName == null ? "id" : $uniqueFieldName;
        $modelName = AUTH_CONFIG['model'];
        $model = new $modelName;
        $res = $model->getFirst("$field='$uniqueFieldValue'");
        if(!empty($res)){
            $passField = AUTH_CONFIG['password_column'];
            unset($res[$modelName][$passField]);
            $_SESSION['Auth'] = $res;
            session_id("user".$res[$modelName]['id']);
            session_regenerate_id();
            return true;
        }
        return false;
    }
}