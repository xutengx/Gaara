<?php
/**
 * 引入html公用组件, 原则上动态赋值应该有且仅有$_SESSION & $_COOKIE 及公用值(如时间,天气)
 * User: Administrator
 * Date: 2016/1/21 0021
 * Time: 19:17
 */
namespace Main;
class template {
    // 跳转中间页面
    private static $jumpTo =  'jumpTo';
    // ajax发送中的蒙层
    private static $ajaxSending =  'ajaxSending';

    public static function show($file){
        include ROOT.'Application/'.APP.'/View/template/'.$file.'.html';
        return true;
    }
    public static function jumpTo($message, $jumpUrl='index?path=index/index/indexDo/'){
        $waitSecond = 3;
        include ROOT.'Main/Views/tpl/'.self::$jumpTo.'.html';
        exit;
    }
    public static function ajaxSending(){
        include ROOT.'Main/Views/tpl/'.self::$ajaxSending.'.html';
    }
}