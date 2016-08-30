<?php
require __DIR__ . DS . '../vendor/autoload.php';

function setReporting() {
    $settings = SkayaPHP\Factories\SettingsFactory::getAll();
    
    if ($settings['DEV_MODE'] == true) {
        error_reporting(E_ALL);
        ini_set('display_errors','On');
    } else {
        error_reporting(E_ALL);
        ini_set('display_errors','Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
    }
}
/** Check for Magic Quotes and remove them **/
function stripSlashesDeep($value){
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}

function removeMagicQuotes() {
    $_GET = stripSlashesDeep($_GET);
    $_POST = stripSlashesDeep($_POST);
    $_COOKIE = stripSlashesDeep($_COOKIE);
}

/** Check register globals and remove them **/
function unregisterGlobals() {
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/** Main Call Function **/
function callHook() {
    $settings = SkayaPHP\Factories\SettingsFactory::getAll();
    global $url;

    $urlArray = explode("/",$url);

    $controller = $urlArray[0];
    array_shift($urlArray);
    if(isset($urlArray[0])) {
        $action = $urlArray[0];
    } else {
        $action = $settings['DEFAULT_ACTION'];
    }
    array_shift($urlArray);
    $queryString = $urlArray;

    $controllerName = $controller;
    $controller = ucwords($controller);
    $model = rtrim($controller, 's');
    $controller .= 'Controller';
    
    if($controllerName == ""){
        $controllerName = $settings['defaultRoute']['controller'];
        $controller = ucwords($controllerName);
        $model = rtrim($controller, 's');
        $controller .= 'Controller';
        $action = $settings['defaultRoute']['action'];
    }

    $dispatch = new $controller($model, $controllerName, $action);
    if ((int)method_exists($controller, $action)) {
        call_user_func_array(array($dispatch,$action),$queryString);
        call_user_func(array($dispatch,'afterFilter'));
    } else {
        $_SESSION['errors'][] = "Cannot find action $action inside $controller";
    }
}

setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();

