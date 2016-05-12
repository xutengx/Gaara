<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/21 0021
 * Time: 17:12
 */
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Route{
    public static $conf       = null;
    private static $url;
    private static $urlArr = array(
        'application'   => 'index',
        'controller'    => 'indexContr',
        'method'        => 'indexDo',
        'pramers'       => array()
    );
    private static $urlPars = array();

    public static function Start(){
        self::getConf();
        //获取路由路径
        self::getUrl();
        //获取不包含路由路径的url中的get参数
        self::getPars();
        //由路由路径,解析路径和参数
        self::getController();
        //整合参数并执行
        self::doMethod();
    }
    private static function getConf(){
        self::$conf = obj('conf');
    }
    private static function getUrl(){
        if( (!isset($_GET[PATH]) || empty($_GET[PATH])) && isset($_GET[MD5(IN_SYS)]) && !empty($_GET[MD5(IN_SYS)]) ) $_GET[PATH] = $_GET[MD5(IN_SYS)];
        if(isset($_GET[PATH]) && !empty($_GET[PATH])){
            $str            = $_GET[PATH];
            $str            = explode('?',$str);
            self::$url      = trim($str[0],'/');
        }else self::$urlArr['application'] = substr_replace(IN_SYS, '',-4 ,4 ); // index.php -> index
    }
    private static function getController(){
        $ary_se     = explode('/', self::$url);
        $se_count   = count($ary_se);
        if($se_count > 2 && ($se_count-3)%2 == 0 ){
            self::$urlArr['application'] = $ary_se[0];
            self::$urlArr['controller'] = $ary_se[1].'Contr';
            self::$urlArr['method']     = $ary_se[2];
            for($i = 3 ; $i < $se_count ; $i = $i+2){
                $ary_kv_hash = array(strtolower($ary_se[$i])=>$ary_se[$i+1]);
                self::$urlArr['pramers']=array_merge(self::$urlArr['pramers'], $ary_kv_hash);
            }
        }
    }
    private static function doMethod(){
        $obj = 'App\\'.self::$urlArr['application'].'\Controller\\'.self::$urlArr['controller'];
        if(file_exists('App/'.self::$urlArr['application'].'/Controller/'.self::$urlArr['controller'].'.class.php')){
            define('APP',self::$urlArr['application']);
            define('VIEW','Application/'.self::$urlArr['application'].'/View/');
            self::$urlArr['pramers'] = array_merge(self::$urlArr['pramers'], self::$urlPars);
            self::filterPars();
            $obj        = obj($obj);
            $func = method_exists($obj,self::$urlArr['method'] ) ? self::$urlArr['method'] : 'indexDo';
            $obj->$func();
        }else header('Location:'.IN_SYS);
    }
    private static function getPars(){
        $str    = str_replace(PATH.'='.self::$url, '', $_SERVER['QUERY_STRING']);
        $str    = explode('?',$str);
        $n = count($str);
        for( $i = 0 ; $i < $n ; $i++ ){
            self::$urlPars = array_merge(self::getParameter($str[ $i ]), self::$urlPars);
        }
    }
    // 获取一段url上的get参数
    private static function getParameter($str){
        $data = array();
        $parameter = explode('&', $str);
        foreach($parameter as $val){
            if(strpos($val, '=')){
                $tmp = explode('=', $val);
                $data[$tmp[0]] = $tmp[1];
            }
        }
        return $data;
    }
    // 参数过滤
    private static function filterPars(){
       obj('F',true,self::$urlArr['pramers']);
    }
}