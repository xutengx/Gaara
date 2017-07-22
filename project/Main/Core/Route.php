<?php

namespace Main\Core;

defined('IN_SYS') || exit('ACC Denied');

class Route {
    
    private static $conf = null;
    
    private static $pathInfo = null;
    
    private static $routeRule = null;
    
    public static function Start() {
        self::$conf = obj('conf');
        // 
        self::getPathInfo();
        // 
        self::getRouteRule();
        // 分析路由路径
        self::routeAnalysis();
        //获取不包含路由路径的url中的get参数
        if (!CLI)
            self::getPars();
        //由路由路径,解析路径和参数
        self::getController();
        //整合参数并执行
        self::doMethod();
    }
    
    
    
    private static function contrAnalysis(){
        
    }
    
    
    
    private static function routeAnalysis(){
        foreach (self::$routeRule as $rule => $contr){
            $param = [];
            $pathInfoPreg = self::ruleToPreg($rule, $param);
//            var_dump($param);
            if(preg_match($pathInfoPreg, self::$pathInfo, $request)){
                unset($request[0]);
                if(empty($request)){
                    $request[] = obj('F');
                }
                $temp = explode('@', $contr);
                self::statistic();
                
                obj('\Main\Core\Response');
                $return = call_user_func_array(array(obj($temp[0]), $temp[1]), $request);  
                obj('\Main\Core\Response')->returnData($return);
            }
        }
        obj('\Main\Core\Response')->returnData('', false, 404);
    }
    
    
    
    private static function getRouteRule(){
        return self::$routeRule = require(ROOT.'Route/'.self::getRouteType().'.php');
    }
    
    /**
     * 分析url,得到pathinfo  eg:http://192.168.64.128/git/php_/project/user/login/123/11?id=12 -> user/login/123/11
     * @return string
     */
    private static function getPathInfo() {
        return self::$pathInfo = \str_replace(\str_replace(\IN_SYS, '', $_SERVER['DOCUMENT_URI']),'',$_SERVER['REQUEST_URI']);
    }
  
    /**
     * 分析当前执行环境 CLI/http ?
     * @return bool
     */
    private static function getRouteType() {
        return CLI ? 'cli' : 'http';
    }
    /**
     * 将路由规则翻译为正则表达式
     * @param string $rule  url规则
     * @param array $arr    url上的形参组成的一维数组
     * @return string       正则表达式
     */
    private static function ruleToPreg($rule = '', &$arr = []){
        $temp = explode('/', $rule); 
        foreach($temp as $k => $v){
            $temp[$k] = \preg_replace_callback("/{.*}/is", function($matches) use (&$arr){
                $arr[] = trim(trim($matches[0], '}'), '{');
                return '([^/]*)';
            }, $v);
        }
        return '#^'.implode('/', $temp).'/?$#';
    }

    // 参数过滤
//    private static function filterPars() {
//        return obj('F', true, self::$urlArr['pramers']);
//    }
    
    // 运行统计
    private static function statistic() {
        $GLOBALS['statistic']['_initTime'] = microtime(true);
        $GLOBALS['statistic']['_initMemory'] = memory_get_usage();
    }
   
}
