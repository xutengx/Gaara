<?php
defined('IN_SYS')||exit('ACC Denied');
return array(
    // 时区
    'timezone'=>'PRC',
    
    // 文件编码
    'char' => 'UTF-8',
    
    // 错误提示
    'debug'=>true,
    
    // 通用前缀
    'tablepre'=>'',
    
    'session'   => require (CONFIG.'session.php'),
    'wechat'    => require (CONFIG.'wechat.php'),
    'db'        => require (CONFIG.'db.php'),
    'cache'     => require (CONFIG.'cache.php'),
    'datatables'=> require (CONFIG.'datatables.php'),
    
    
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