<?php
defined('IN_SYS')||exit('ACC Denied');
return array(
    'path'=>'path',                            // 路由关键字      // 全局PATH常量
    'timezone'=>'PRC',                         // 时区
    'sessionModuleName'=>'redis',             // session存储方式  user|file|redis 若为redis,则在php.ini中配置(建议)
    'sessionPath'=>'data/Session',           // session存储路径 // 全局SESSIONPATH常量
    'sessionLife'=>3600*24*7,                  // session 时效性  // 全局SESSIONLIFE常量
    'sessionHostOnly'=>true,                  // session
    'sessionAutoStart'=>true,                  // session 自动开启

    'cacheDriver'=>'redis',                     // 缓存存储方式  redis|file                  
    
    'appid_mq'=>'wx7cdc6e6a298c7d80',
    'appsecret_mq'=>'e0c47bb90180097a38b21953a5bb2954',
//    'debug_mq'=>false,
    //服务器的信息
//    121.40.86.93 root 1d3283e6

    'appid_test'=>'wx8f0ca1bc115c1fae',
    'appsecret_test'=>'d4624c36b6795d1d99dcf0547af5443d',

    'db_mq' => array(
        'write'=>array(
            array(
                'weight'=>10,
                'type'=>'mysql',
                'host'=>'127.0.0.1',
                'port'=>3306,
                'user'=>'root',
                'pwd'=>'d3e2b90ee3',
                'char'=>'UTF8',
                'db'=>'file_system'
            )
        )
    ),
    'db_test'=>array(
        'write'=>array(
            array(
                'weight'=>10,
                'type'=>'mysql',
                'host'=>'127.0.0.1',
                'port'=>3306,
                'user'=>'root',
                'pwd'=>'root',
                'char'=>'UTF8',
                'db'=>'file_system'
            )
        ),
        'read'=>array(
            array(
                'weight'=>1,
                'type'=>'mysql',
                'host'=>'127.0.0.1',
                'port'=>3306,
                'user'=>'root',
                'pwd'=>'root',
                'char'=>'UTF8',
                'db'=>'file_system'
            ),
            array(
                'weight'=>2,
                'type'=>'mysql',
                'host'=>'127.0.0.1',
                'port'=>3306,
                'user'=>'root',
                'pwd'=>'root',
                'char'=>'UTF8',
                'db'=>'file_system'
            )
        )
    ),

    'debug'=>true,
    'minjs'=>true,
    'tablepre'=>'file_',
    /**
     * @return string 多配置关键字
     */
    'chooseConfig'=> function(){
        if(isset($_SERVER['HTTP_HOST']) && ( $_SERVER['HTTP_HOST'] == 'hi.misbike.com')){
            return '_mq';
        }else if( (isset($_SERVER['HTTP_HOST']) && ( $_SERVER['HTTP_HOST'] == '123.206.8.25') )
            || (isset($_SERVER['HOSTNAME']) && ( $_SERVER['HOSTNAME'] == 'VM_61_217_centos') ) ){
            return '_hk';
        }return '_test';
    }
);