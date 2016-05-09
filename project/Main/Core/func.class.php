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
 * 依赖 template.class.php 指向 template::show()
 * @param string $template 引入模板名
 */
function template($template=''){
    if($template) \Main\Core\template::show($template);
    else throw new \Exception('引入模板名有误!');
}

// 运行状态统计
function statistic(){
    global $statistic;
    $runtime = ( microtime( true ) - $statistic['_beginTime'] ) * 1000; //将时间转换为毫秒
    $usedMemory = ( memory_get_usage() - $statistic['_beginMemory'] ) / 1024;
    $time = obj( 'mysql' )->queryTimes;
    echo "<br /><br />运行时间: {$runtime} 毫秒<br />";
    echo "耗费内存: {$usedMemory} K<br />";
    echo "数据库操作次数: {$time} 次<br /><br /><br />";
}
/**
 * 发送HTTP状态
 * @param integer $code 状态码
 * @return void
 */
function send_http_status($code) {
    static $_status = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ',  // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );
    if(isset($_status[$code])) {
        header('HTTP/1.1 '.$code.' '.$_status[$code]);
        // 确保FastCGI模式下正常
        header('Status:'.$code.' '.$_status[$code]);
    }
}
