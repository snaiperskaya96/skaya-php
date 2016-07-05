<?php
define('DEV_MODE',true);

define('BASEPATH','/');
define('ROOTPATH',dirname(__DIR__));

define('DEFAULT_ACTION','index');
define('DEFAULT_TITLE','');
define('DEFAULT_LAYOUT','default');

define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_HOST', '');

const defaultRoute = [
    'controller' => 'pages',
    'action' => 'home'
];

const plugins = [];

define('ACP_GUEST',0);
define('ACP_ALLOW_EVERYONE',-1);
define('ACP_DENY_EVERYONE',-2);

const AUTH_CONFIG = [
    'model' => 'User',
    'username_column' => 'username', // or maybe email
    'password_column' => 'password', // if you're using the AuthComponent to handle sessions,
    // take in mind that the BCRYPT hash will be 60 chars long
    'acp_column' => null,            // (optional - null to disable) defines the column that will be used for access control
    'salt' => 'randomCharacters123', // please specify your salt here
    'cpu_cost' => 10, // choose a reasonable value from 4 to 31
];