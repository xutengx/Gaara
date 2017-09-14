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
 * 重定向到指定路由
 * @param string $where 指定路由,如:index/index/indexDo/
 * @param array $pars   跳转中间页显示信息|不使用中间页
 * @param string $msg   参数数组
 * @throws \Exception
 */
function headerTo(string $where = '', array $pars = array(), string $msg = null) {
    $url = url($where, $pars);
    !is_null($msg) ? obj(Template::class)->jumpTo($url, $msg) : exit(header('Location:' . $url));
}

/**
 * 读取环境变量
 * @param string $envname
 * @param type $default
 * @return type
 */
function env(string $envname, $default = null){
    return obj(Conf::class)->getEnv($envname, $default);
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
    // 当前总体消耗内存
    $nowMemory = ( memory_get_usage() ) / 1024;
    // 当前程序消耗内存
    $now2Memory = ( memory_get_usage() - $statistic['_beginMemory']) / 1024;
  
    $data = [
        '框架初始化消耗内存' => $initMemory .'K',
        '框架初始化消耗时间' => $initTime .'毫秒',
        '程序消耗内存峰值' => $usedMemory .'K',
        '当前程序消耗内存' => $now2Memory .'K',
        '当前总体消耗内存' => $nowMemory .'K',
        '总体消耗内存峰值' => $totleMemory .'K',
        '总体消耗时间' => $runtime .'毫秒',
    ];
    
    return $data;
}