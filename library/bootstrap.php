<?php
require_once (ROOT . DS . 'library' . DS . 'default.php');
if(file_exists(ROOT . DS . 'config' . DS . 'config.php'))
	require_once (ROOT . DS . 'config' . DS . 'config.php');
if(file_exists(ROOT . DS . 'config' . DS . 'auth.config.php'))
	require_once (ROOTPATH . DS . 'config' . DS . 'auth.config.php');
require_once (ROOT . DS . 'library' . DS . 'shared.php');
