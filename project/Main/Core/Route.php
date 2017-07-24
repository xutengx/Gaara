<?php

namespace Main\Core;

defined('IN_SYS') || exit('ACC Denied');
/**
 * 显式自定义路由
 */
class Route {
    
    private static $pathInfo = null;
    
    private static $routeType = null;
    
    private static $routeRule = null;
    
    public static function Start() {
        // 得到 $pathInfo
        self::$pathInfo = self::getPathInfo();
        // 得到当前模式 cli ?
        self::$routeType = self::getRouteType();
        // 引入route规则
        self::$routeRule = self::getRouteRule();
        // 设置session
        self::startSession();
        // 分析路由, 并执行
        self::routeAnalysis();
    }
    /**
     * 开启session
     */
    private static function startSession(){
        obj('session');
    }
    
    /**
     * 方法执行,支持闭包函数
     * @param string|function $contr 将要执行的方法
     * @param array|object $request 请求参数
     * @return void
     */
    private static function doMethod($contr, $request){
        self::statistic();
        obj('\Main\Core\Response');
        // 形如 'App\index\Contr\IndexContr@indexDo'
        if(is_string($contr)){
            $temp = explode('@', $contr);
            $return = call_user_func_array(array(obj($temp[0]), $temp[1]), $request);  
        }
        // 形如 function($param_1, $param_2 ) {return 'this is a function !';}
        else{
            $return = call_user_func_array($contr, $request);
        }
        obj('\Main\Core\Response')->returnData($return);
    }
    
    /**
     * 参数分析
     * @param array $parameter  形参数组列表(一维数组)
     * @param array $argument   实参数组列表(一维数组)
     * @return array|obj 可调用的参数数组(一维链表)|
     */
    private static function paramAnalysis($parameter, $argument) {
        $arr = [];
        if (empty($parameter)) {
            $arr[] = obj('F');
        } else {
            foreach ($parameter as $k => $v) {
                $arr[$v] = ($argument[$k + 1] === '') ? null : $argument[$k + 1];
            }
            obj('F', true, $arr);
        }
        return $arr;
    }

    /**
     * 路由分析
     */
    private static function routeAnalysis(){
        foreach (self::$routeRule as $rule => $contr){
            $parameter = [];
            $pathInfoPreg = self::ruleToPreg($rule, $parameter);
            // 确定路由匹配
            if(preg_match($pathInfoPreg, self::$pathInfo, $argument)){     
                // 确认参数
                $request = self::paramAnalysis($parameter , $argument);
                // 执行方法
                return self::doMethod($contr, $request);
            }
        }
        obj('\Main\Core\Response')->returnData('', false, 404);
    }
    
    
    /**
     * 得到当前应该使用的route配置
     * @return type
     */
    private static function getRouteRule(){
        return require(ROUTE.self::getRouteType().'.php');
    }

    /**
     * 分析当前执行环境 CLI/http ?
     * @return type
     */
    private static function getRouteType() {
        return CLI ? 'cli' : 'http';
    }
    
    /**
     * 分析url,得到pathinfo  eg:http://192.168.64.128/git/php_/project/user/login/123/11?id=12 -> /user/login/123/11
     * @return string
     */
    private static function getPathInfo() {
        return '/' . \str_replace('?' . $_SERVER['QUERY_STRING'], '', \str_replace(\str_replace(\IN_SYS, '', $_SERVER['DOCUMENT_URI']), '', $_SERVER['REQUEST_URI']));
    }

    /**
     * 将路由规则翻译为正则表达式
     * @param string    $rule       url规则
     * @param array     &$param     url上的形参组成的一维数组
     * @return string               正则表达式
     * @return array    $param      形参数组
     */
    private static function ruleToPreg($rule = '', &$param = []){
        $temp = explode('/', $rule); 
        foreach($temp as $k => $v){
            $key = false;
            $temp[$k] = \preg_replace_callback("/{.*\?}/is", function($matches) use (&$param, &$key){
                $param[] = trim(trim($matches[0], '}'), '{');
                $key = true;
                return '?([^/]*)';
            }, $v);
            if($key)                continue;
            $temp[$k] = \preg_replace_callback("/{.*}/is", function($matches) use (&$param){
                $param[] = trim(trim($matches[0], '}'), '{');
                return '([^/]+)';
            }, $v);
        }
        return '#^'.implode('/', $temp).'[/]?$#';
    }
    
    // 运行统计
    private static function statistic() {
        $GLOBALS['statistic']['_initTime'] = microtime(true);
        $GLOBALS['statistic']['_initMemory'] = memory_get_usage();
    }
   
}
