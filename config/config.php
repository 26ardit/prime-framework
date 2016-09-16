<?php

define('BASEPATH', realpath(__DIR__.'\..'));

return [
    'mysql' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'db' => 'sekreti'
    ],
    'cookie' => [
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ],
    'session' => [
        'session_name' => 'user',
        'token_name' => 'token'
    ]
];
