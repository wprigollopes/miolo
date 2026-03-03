<?php

return [
    'db' => [
        'admin_ldap' => [
            'system'   => 'postgres',
            'host'     => 'localhost',
            'port'     => '5432',
            'name'     => 'CHANGE_ME',
            'user'     => 'CHANGE_ME',
            'password' => 'CHANGE_ME',
        ],
    ],
    'login' => [
        'ldap' => [
            'host'      => 'CHANGE_ME',
            'port'      => '389',
            'base'      => 'dc=example,dc=com',
            'user'      => 'cn=Admin,dc=example,dc=com',
            'password'  => 'CHANGE_ME',
            'userName'  => 'cn',
            'userEmail' => 'mail',
            'schema'    => 'system',
        ],
    ],
];
