<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Route{
    private static $conf       = null;
    private static $url;
    private static $urlArr = array(
        'application'   => 'index',
        'controller'    => 'indexContr',
        'method'        => 'indexDo',
        'pramers'       => array()
    );
    private static $urlPars = array();

    public static function Start(){
        self::$conf = obj('conf');
        //获取路由路径
        self::getUrl();
        //获取不包含路由路径的url中的get参数
        if(!CLI) self::getPars();
        //由路由路径,解析路径和参数
        self::getController();
        //整合参数并执行
        self::doMethod();
    }
    private static function getUrl(){
        if( (!isset($_GET[PATH]) || empty($_GET[PATH])) && isset($_GET[MD5(IN_SYS)]) && !empty($_GET[MD5(IN_SYS)]) )
            $_GET[PATH] = $_GET[MD5(IN_SYS)];
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
        $obj = 'App\\'.self::$urlArr['application'].'\Contr\\'.self::$urlArr['controller'];
        if(file_exists(ROOT.'App/'.self::$urlArr['application'].'/Contr/'.self::$urlArr['controller'].'.class.php')){
            self::defineV(self::$urlArr['application']);
            self::$urlArr['pramers'] = array_merge(self::$urlArr['pramers'], self::$urlPars);
            if(!CLI) {
                self::filterPars();
            }
            //设置session
            obj('session');
//            $func = method_exists($obj,self::$urlArr['method'] ) ? self::$urlArr['method'] : 'indexDo';
            $func = self::$urlArr['method'];
            $obj  = obj($obj, true, $func);
            $obj->$func();
        }else header('Location:'.IN_SYS);
    }
    private static function getPars(){
        $str    = str_replace(PATH.'='.self::$url, '', $_SERVER['REQUEST_URI']);
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
    // 定义部分常量
    private static function defineV($app){
        define('APP',$app);
        $script_name = str_replace(IN_SYS, '',$_SERVER['SCRIPT_NAME']);
        $host = isset($_SERVER['HTTP_HTTPS']) ? $_SERVER['HTTP_HTTPS'] : $_SERVER['REQUEST_SCHEME'];  // nginx 自定配置 proxy_set_header https https;
        define('HOST',$host.'://'.$_SERVER['HTTP_HOST'].$script_name); 
        define('VIEW',HOST.'App/'.$app.'/View/');
    }
    // 参数过滤
    private static function filterPars(){
       obj('F',true,self::$urlArr['pramers']);
    }
}