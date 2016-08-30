<?php
return [
    'settings' => [
        'DEV_MODE' => true,

        'BASEPATH' => '/',
        'ROOTPATH' => dirname(__DIR__),

        'DEFAULT_ACTION' => 'index',

        'DEFAULT_TITLE' => '',
        'DEFAULT_LAYOUT' => 'default',

        'DB_NAME' => 'skayaphp',
        'DB_USER' => 'skayaphp',
        'DB_PASSWORD' => 'skayaphppass',
        'DB_HOST' => 'localhost',

        'defaultRoute' => [
            'controller' => 'pages',
            'action' => 'home'
        ],

        'plugins' => []
    ],
    'auth' => [
        'AUTH_CONFIG' => [
            'model' => 'User',
            'username_column' => 'username',
            'password_column' => 'password',
            'acp_column' => null,
            'salt' => 'randomCharacters123', 
            'cpu_cost' => 10,
        ],
        'ACP_GUEST' => 0,
        'ACP_ALLOW_EVERYONE' => -1,
        'ACP_DENY_EVERYONE' => -2,
    ]
];