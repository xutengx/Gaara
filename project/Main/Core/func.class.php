<?php
/**
 * 柯里化,多态
 * User: Administrator
 * Date: 2016/2/4 0004
 * Time: 10:54
 */
/**
 * 实例化对象,包含自动加载
 * 依赖 loader.class.php 指向 obj::get()
 * @param $class
 * @param bool|true $app 当$class为Contr或Module时,代表所指向的APP,默认当前APP;
 *                        当$class为其他引用类时,其他参数生效,$app默认为true,代表单例模式实例化;
 * @param 其他参数, 在new非Contr或Module对象时的参数, 注:单例模式下,显然只有第一次实例化时,参数才会被使用!
 * @return mixed 对象
 */
function obj($obj, $app=true){
    $arr = func_get_args();
    unset($arr[0]);
    unset($arr[1]);
    return \Main\Core\loader::get($obj, $app, $arr);
}

/**
 * 清除所有对象缓存
 * @return bool
 */
function delobj(){
    return \Main\Core\loader::unsetAllObj();
}

/**
 * 依赖 template.class.php 指向 template::show()
 * @param string $template 引入模板名
 */
function template($template=''){
    if($template) obj('\Main\Core\template')->show($template);
    else throw new \Exception('引入模板名有误!');
}
/**
 * 重定向到指定路由
 * @param string        $where 指定路由,如:index/index/indexDo/
 * @param string|false  $msg   跳转中间页显示信息|不使用中间页
 * @param array         $pars  参数数组
 */
function headerTo($where='', $msg = false, array $pars = array()){
    $str = '';
    foreach($pars as $k=>$v){
        $str .= $k.'/'.$v.'/';
    }
    $where = IN_SYS.'?'.PATH.'='.trim($where, '/').'/'.$str;
        $t = ( ( $msg!==false ) ? obj('template')->jumpTo($msg, $where) : header('location:'.$where) );
        if(!$t) throw new \Exception;
}

/**
 * 异步执行
 * @param string        $where  指定路由,如:index/index/indexDo/
 * @param array         $pars   参数数组
 * @param string        $scheme http/https
 * @param string        $host   异步执行的服务器ip
 */
function asynExe($where='', array $pars = array(), $scheme = 'http', $host = '127.0.0.1'){
    $where = trim($where, '/').'/';
    foreach($pars as $k=>$v){
        $where .= $k.'/'.$v.'/';
    }
    $url = $scheme.'://'.$host.$_SERVER['SCRIPT_NAME'].'?'.PATH.'='.$where;
   
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1);
    curl_exec($ch);
    curl_close($ch);
    return true;
}

// 运行状态统计
function statistic(){
    global $statistic;
    $runtime = ( microtime( true ) - $statistic['_beginTime'] ) * 1000; //将时间转换为毫秒
    $usedMemory = ( memory_get_usage() - $statistic['_beginMemory'] ) / 1024;
//    $time = obj( 'mysql' )->queryTimes;
    echo "<br /><br />运行时间: {$runtime} 毫秒<br />";
    echo "耗费内存: {$usedMemory} K<br />";
//    echo "数据库操作次数: {$time} 次<br /><br /><br />";
}