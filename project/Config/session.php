<?php

defined('IN_SYS')||exit('ACC Denied');
return [
    // session 时效性
    'lifetime'=>3600*24*7,

    // session js不获取cookie
    'httponly'=>true,
     
    // session 自动开启
    'autostart'=>true,
    
    // session 驱动
    'driver' => 'redis',
    
    // redis 做 session 驱动时的配置参数
    'redis' => [
        'host'  => '127.0.0.1',
        'port'  => 6379,
        'passwd'  => null,
    ],
    
    // file 做 session 驱动时的配置参数
    'file' => [
        'dir'  => 'data/Session'
    ],
    
    // mysql 做 session 驱动时的配置参数
    'mysql' => [
        
    ],
    
];