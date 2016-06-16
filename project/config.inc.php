<?php
defined('IN_SYS')||exit('ACC Denied');
return [
    'path'=>'path',                         // 路由关键字      // 全局PATH常量
    'timezone'=>'PRC',                       // 时区
    'sessionModuleName'=>'user',              // session存储方式  user|file
    'sessionPath'=>'data/Session',           // session存储路径 // 全局SESSIONPATH常量
    'sessionLife'=>3600*24*7,                 // session 时效性  // 全局SESSIONLIFE常量
    'sessionHostOnly'=>true,                 // session

    'appid_poster'=>'wx996bd5d838d5d827',
    'appsecret_poster'=>'d3927177ebc315da18681dd9876ed073',
    'debug_poster'=>false,

    'appid_wx'=>'wxf104d7a120384fec',
    'appsecret_wx'=>'10b05e039778ec7fdb6344b969ed4c1e',

    'appid_test'=>'wx8f0ca1bc115c1fae',
    'appsecret_test'=>'d4624c36b6795d1d99dcf0547af5443d',

    'db'=>[
        'write'=>[
            [
                'weight'=>10,
                'type'=>'mysql',
                'host'=>'10.4.17.219',
                'port'=>3306,
                'user'=>'root',
                'pwd'=>'Huawei$123#_',
                'char'=>'UTF8',
                'tablepre'=>'hk_',
                'keytable'=>'user',
                'db'=>'hk'
            ]
        ],
        'read'=>[
            [
                'weight'=>10,
                'type'=>'mysql',
                'host'=>'10.4.17.219',
                'port'=>3306,
                'user'=>'root',
                'pwd'=>'Huawei$123#_',
                'char'=>'UTF8',
                'tablepre'=>'hk_',
                'keytable'=>'user',
                'db'=>'hk'
            ]
        ]
    ],
    'db_hk' => [
        'write'=>[
            [
                'weight'=>10,
                'type'=>'mysql',
                'host'=>'127.0.0.1',
                'port'=>3306,
                'user'=>'root',
                'pwd'=>'Passwd@123456',
                'char'=>'UTF8',
                'tablepre'=>'hk_',
                'keytable'=>'user',
                'db'=>'hk'
            ]
        ],
        'read' => [
            [
                'weight'=>10,
                'type'=>'mysql',
                'host' => '127.0.0.1',
                'port'=>3306,
                'user' => 'root',
                'pwd' => 'Passwd@123456',
                'char'=>'UTF8',
                'tablepre'=>'hk_',
                'keytable'=>'user',
                'db' => 'hk'
            ]
        ]
    ],
    'db_test'=>[
        'write'=>[
            [
                'weight'=>10,
                'type'=>'mysql',
                'host'=>'127.0.0.1',
                'port'=>3306,
                'user'=>'root',
                'pwd'=>'root',
                'char'=>'UTF8',
                'tablepre'=>'hk_',
                'keytable'=>'user',
                'db'=>'hk'
            ]
        ],
        'read'=>[
            [
                'weight'=>1,
                'type'=>'mysql',
                'host'=>'127.0.0.1',
                'port'=>3306,
                'user'=>'root',
                'pwd'=>'root',
                'char'=>'UTF8',
                'tablepre'=>'hk_',
                'keytable'=>'user',
                'db'=>'hk'
            ],
            [
                'weight'=>2,
                'type'=>'mysql',
                'host'=>'127.0.0.1',
                'port'=>3306,
                'user'=>'root',
                'pwd'=>'root',
                'char'=>'UTF8',
                'tablepre'=>'hk_',
                'keytable'=>'user',
                'db'=>'hk'
            ]
        ]
    ],

    'debug'=>true,
    'minjs'=>true,
    'tablepre'=>'hk_',
//    'keytable'=>'hk_user',
//    'char'=>'UTF8',
    /**
     * @return string 多配置关键字
     */
    'chooseConfig'=> function(){
        if(isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == 'poster.issmart.com.cn')){
            return '_poster';
        }else if(isset($_SERVER['HTTP_HOST']) && ( $_SERVER['HTTP_HOST'] == 'wx.issmart.com.cn')){
            return '_wx';
        }else if( (isset($_SERVER['HTTP_HOST']) && ( $_SERVER['HTTP_HOST'] == '123.206.8.25') )
            || (isset($_SERVER['HOSTNAME']) && ( $_SERVER['HOSTNAME'] == 'VM_61_217_centos') ) ){
            return '_hk';
        }return '_test';
    },
//    'mima' => 'Passwd@123456'
];