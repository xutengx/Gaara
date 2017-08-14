<?php

/**
 * 单例 实例化对象
 * 依赖 Integrator.class.php 指向 obj::get()
 * @param $class
 * @param 其他参数, 注:单例模式下,显然只有第一次实例化时,参数才会被使用!
 * @return mixed 对象
 */
function obj(string $obj) {
    $arr = func_get_args();
    unset($arr[0]);
    return \Main\Core\Integrator::get($obj, $arr);
}

/**
 * 依赖执行方法
 * @param string|object $obj 类|对象
 * @param string $methodName
 * @return type
 */
function run($obj, string $methodName){
    $param = func_get_args();
    unset($param[0]);
    unset($param[1]);
    return \Main\Core\Integrator::run($obj, $methodName, $param);
}

/**
 * 普通 实例化对象
 * @param $class
 * @param 其他参数
 * @return mixed 对象
 */
function dobj($obj) {
    $arr = func_get_args();
    unset($arr[0]);
    return new $obj(...$arr);
}

function url(string $string = '', array $param = []): string{
    $url = HOST.ltrim($string, '/');
    if(!empty($param)){
        $url .= '?'.http_build_query($param);
    }
    return $url;
}

/**
 * 清除所有对象缓存
 * @return bool
 */
function delobj() {
    return \Main\Core\Integrator::unsetAllObj();
}

/**
 * 依赖 template.php 指向 template::show()
 * @param string $template 引入模板名
 */
function template($template = '') {
    if ($template)
        obj('\Main\Core\template')->show($template);
    else
        throw new \Exception('引入模板名有误!');
}

/**
 * 目前不兼容新路由
 * 重定向到指定路由
 * @param string        $where 指定路由,如:index/index/indexDo/
 * @param string|false  $msg   跳转中间页显示信息|不使用中间页
 * @param array         $pars  参数数组
 */
function headerTo($where = '', $msg = false, array $pars = array()) {
    $str = '';
    foreach ($pars as $k => $v) {
        $str .= $k . '/' . $v . '/';
    }
    $where = IN_SYS . '?' . PATH . '=' . trim($where, '/') . '/' . $str;
    $t = ( ( $msg !== false ) ? obj('template')->jumpTo($msg, $where) : header('location:' . $where) );
    if (!$t)
        throw new \Exception;
}

// 运行状态统计
function statistic() {
    global $statistic;
    // 框架初始化消耗时间
    $initTime = ( $statistic['_initTime'] - $statistic['_beginTime'] ) * 1000; //将时间转换为毫秒
    // 框架初始化消耗内存
    $initMemory = ( $statistic['_initMemory'] - $statistic['_beginMemory'] ) / 1024;
    // 总体消耗时间
    $runtime = ( microtime(true) - $statistic['_beginTime'] ) * 1000; //将时间转换为毫秒
    // 程序消耗内存峰值
    $usedMemory = ( memory_get_peak_usage() - $statistic['_beginMemory'] ) / 1024;
    // 总体消耗内存峰值
    $totleMemory = ( memory_get_peak_usage() ) / 1024;
    
    $report = '<br /><br />';
    $report .= "框架初始化消耗时间 : {$initTime} 毫秒<br />";
    $report .= "总体消耗时间 : {$runtime} 毫秒<br />";
    $report .= "框架初始化消耗内存 : {$initMemory} K<br />";
    $report .= "程序消耗内存峰值 : {$usedMemory} K<br />";
    $report .= "总体消耗内存峰值 : {$totleMemory} K<br />";
    echo $report;
}