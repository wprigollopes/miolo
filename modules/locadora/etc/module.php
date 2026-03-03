<?php

return [
    'db' => [
        'locadora' => [
            'system'   => 'sqlite',
            'host'     => 'localhost',
            'name'     => '/usr/local/miolo2/modules/locadora/sql/locadora.sqlite',
            'user'     => 'CHANGE_ME',
            'password' => 'CHANGE_ME',
        ],
    ],
    'theme' => [
        'module' => 'miolo',
        'main'   => 'system',
        'lookup' => 'system',
        'title'  => 'Miolo Web Application - LOCADORA',
    ],
    'login' => [
        'check' => '0',
    ],
];
