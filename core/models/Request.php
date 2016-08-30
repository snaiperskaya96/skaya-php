<?php

namespace SkayaPHP\Core\Models;

class Request {
    private $_isPost = false;
    private $_isGet = false;
    private $_isAjax = false;
    private $_isOptions = false;
    private $_isDelete = false;
    private $_isHead = false;
    private $_isPut = false;
    public $data = [];
    
    function __construct(){
        $this->checkMethod();
        $this->data = $_REQUEST;
        if(isset($this->data['url']))
            unset($this->data['url']);
    }
    
    private function checkMethod(){
        $method = $_SERVER['REQUEST_METHOD'];
        $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
        $this->data = $request;
        switch ($method) {
          case 'PUT':
            $this->_isPut = true;
            break;
          case 'POST':
            $this->_isPost = true;
            break;
          case 'GET':
            $this->_isGet = true;
            break;
          case 'HEAD':
            $this->_isHead = true;
            break;
          case 'DELETE':
            $this->_isDelete = true;
            break;
          case 'OPTIONS':
            $this->_isOptions = true;
            break;
          default:
            break;
        }        
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {    
          $this->_isAjax = true;    
        }        
    }

    public function isPost(){
        return $this->_isPost;
    }

    public function isGet(){
        return $this->_isGet;
    }

    public function isAjax(){
        return $this->_isAjax;
    }

    public function isOptions(){
        return $this->_isOptions;
    }

    public function isDelete(){
        return $this->_isDelete;
    }

    public function isHead(){
        return $this->_isHead;
    }

    public function isPut(){
        return $this->_isPost;
    }    
}
