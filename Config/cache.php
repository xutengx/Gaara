<?php

defined('IN_SYS')||exit('ACC Denied');
return [
    'driver' => 'redis',
    
    'redis' => [
        'host'  => '127.0.0.1',
        'port'  => 6379
    ],
    
    'file' => [
        'dir'  => 'data/Cache/'
    ]
];