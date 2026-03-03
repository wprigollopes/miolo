<?php

$config = [
    'common' => [
        'options' => [
            'persistence' => false,
        ],
    ],
];

if (($action ?? '') === 'lookup') {
    $config['login'] = ['check' => false];
}

return $config;
