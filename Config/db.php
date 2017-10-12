<?php

return [
    'default' => env('DB_CONNECTION', '_test'),
    
    'connections' => [
        '_test' => [
            'write' => [
                [
                    'weight' => 10,
                    'type' => 'mysql',
                    'host' => env('db_host'),
                    'port' => 3306,
                    'user' => env('db_user'),
                    'pwd' => env('db_passwd'),
                    'char' => 'UTF8',
                    'db' => env('db_db'),
                ]
            ],
            'read' => [
                [
                    'weight' => 1,
                    'type' => 'mysql',
                    'host' => env('db_host'),
                    'port' => 3306,
                    'user' => env('db_user'),
                    'pwd' => env('db_passwd'),
                    'char' => 'UTF8',
                    'db' => env('db_db'),
                ],
                [
                    'weight' => 1,
                    'type' => 'mysql',
                    'host' => env('db_host'),
                    'port' => 3306,
                    'user' => env('db_user'),
                    'pwd' => env('db_passwd'),
                    'char' => 'UTF8',
                    'db' => env('db_db'),
                ]
            ]
        ],
        '_dev' => [
            'write' => [
                [
                    'weight' => 10,
                    'type' => 'mysql',
                    'host' => '127.0.0.1',
                    'port' => 3306,
                    'user' => 'root',
                    'pwd' => 'root',
                    'char' => 'UTF8',
                    'db' => 'test'
                ]
            ],
            'read' => [
                [
                    'weight' => 1,
                    'type' => 'mysql',
                    'host' => '127.0.0.1',
                    'port' => 3306,
                    'user' => 'root',
                    'pwd' => 'root',
                    'char' => 'UTF8',
                    'db' => 'test'
                ],
                [
                    'weight' => 2,
                    'type' => 'mysql',
                    'host' => '127.0.0.1',
                    'port' => 3306,
                    'user' => 'root',
                    'pwd' => 'root',
                    'char' => 'UTF8',
                    'db' => 'test'
                ]
            ]
        ]
    ]
];
