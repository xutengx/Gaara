<?php

namespace App\jurisdiction;
use \Gaara\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class jurisdictionContr extends Controller {
    protected static $contrRule= [
        'test_admin' => [
            'App\jurisdiction\Contr\enable',
            'App\jurisdiction\Contr\IndexContr',
        ]
    ] ;
    protected static $urlRule= [
        'test_admin' => [
            '别名dis',
            '/enable.html',
            '/power.html',
        ]
    ] ;
    public function __construct() {
        parent::__construct();
        $this->登录状态检查();
        $this->获取当前用户身份();
//        $this->允许访问的控制器();
        $this->允许访问的URL();
    }
    
    protected function 登录状态检查(){
        return true;
    }
    
    protected function 获取当前用户身份(){
        return 'test_admin';
    }
    
    protected function 允许访问的控制器(){
        if(in_array(get_class($this), static::$contrRule[$this->获取当前用户身份()])){
            return true;
        }else exit('没得权限!');
    }
    
    protected function 允许访问的URL(){
        $alias = obj(Request::class)->alias;
        if(in_array($alias, static::$urlRule[$this->获取当前用户身份()]) ){
            return true;
        }else exit('没得权限!');
    }
    
}
