<?php
class SessionComponent extends Component{
	
	public function init(){
		session_start();
	}

	public function write($key,$value){
		$_SESSION[$key] = $value;
	}

	public function read($key){
		if(isset($_SESSION[$key]))
			return $_SESSION[$key];
		else
			return null;
	}
}