<?php

namespace Main\Core;

defined('IN_SYS') || exit('ACC Denied');

/**
 * 处理系统全部响应( 输出 )
 */
class Response {

    // 当前请求类型
    private $requestMethod = '';
    // 当前请求的资源类型
    private $acceptType = '';
    // REST允许的请求类型列表
    private $allowMethod = array('post', 'delete', 'get', 'put', 'patch');
    // 默认的资源类型
    private $defaultType = 'html';
    // REST允许输出的资源类型列表
    private $allowOutputType = array('xml' => 'application/xml', 'json' => 'application/json', 'html' => 'text/html',);

    public function __construct() {
        $this->init();
    }

    public function init() {
//        ob_start();
        $this->getInfo();
    }
    /*
     * 分析请求头中的信息
     */

    private function getInfo() {
        $this->acceptType = $this->getAcceptType();
        $this->requestMethod = $this->getRequestMethod();
    }
    
    /**
     * 返回成功
     */
    public function returnSuccess(){
        
    }
    
    /**
     * 返回失败
     */
    public function returnFail(){
        
    }

    /**
     * 输出返回数据
     * @access protected
     *
     * @param mixed   $data 要返回的数据
     * @param String  $type 返回类型 JSON XML
     * @param integer $code HTTP状态
     *
     * @return void
     */
    public function returnData($data = '', $type = false, $code = 200) {
        $type = $type ? strtolower($type) : $this->acceptType;
        $this->sendHttpHeader($code, $type);
        exit($this->encodeData($data, $type));
    }

    // 发送Http状态信息
    private function sendHttpHeader($code = 200, $type = false) {
        static $_status = array(
            // Informational 1xx
            100 => 'Continue', 101 => 'Switching Protocols', // Success 2xx
            200 => 'OK', 201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', // Redirection 3xx
            300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Moved Temporarily ', // 1.1
            303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', // 306 is deprecated but reserved
            307 => 'Temporary Redirect', // Client Error 4xx
            400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed', // Server Error 5xx
            500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported', 509 => 'Bandwidth Limit Exceeded');
        if (isset($_status[$code]) && !headers_sent()) {
            header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
            // 确保FastCGI模式下正常
            header('Status:' . $code . ' ' . $_status[$code]);
        }
        $charset = !empty($charset) ? $charset : obj('conf')->char;
        if (isset($this->allowOutputType[strtolower($type)]))
            header('Content-Type: ' . $this->allowOutputType[$type] . '; charset=' . $charset);
    }
    
    // 设置Http头信息
    public function setHeaders(array $headers):void{
        foreach($headers as $k => $v)
            $this->setHeader($k.':'.$v);
    }
    
    // 设置Http头信息
    public function setHeader(string $header):void{
        header($header);
    }
    

    /**
     * 编码数据
     * @access protected
     * @param mixed  $data 要返回的数据
     * @param String $type 返回类型 JSON XML
     *
     * @return string
     */
    private function encodeData($data = '', $type = 'json') {
        switch ($type) {
            case 'json':
                return json_encode($data, JSON_UNESCAPED_UNICODE);
            case 'xml':
                return obj('tool')->xml_encode($data);
            case 'php':
                return serialize($data);
            case 'html':
                return is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data;
            default:
                return var_export($data);
        }
    }

    /**
     * 获取当前请求的Accept头信息
     * @return string
     */
    private function getAcceptType() {
        if (!empty($_SERVER['PATH_INFO']))
            return \strtolower(\pathinfo($_SERVER['PATH_INFO'], 4));
        $type = [
            'html'  => ['text/html', 'application/xhtml+xml', '*/*'],
            'xml'   => ['application/xml', 'text/xml', 'application/x-xml'],
            'json'  => ['application/json', 'text/x-json', 'application/jsonrequest', 'text/json'],
            'js'    => ['text/javascript', 'application/javascript', 'application/x-javascript'],
            'css'   => ['text/css'],
            'rss'   => ['application/rss+xml'],
            'yaml'  => ['application/x-yaml,text/yaml'],
            'atom'  => ['application/atom+xml'],
            'pdf'   => ['application/pdf'],
            'text'  => ['text/plain'],
            'png'   => ['image/png'],
            'jpg'   => ['image/jpg,image/jpeg,image/pjpeg'],
            'gif'   => ['image/gif'],
            'csv'   => ['text/csv']
        ];
        foreach ($type as $key => $val) {
            foreach ($val as $v) {
                if (stristr($_SERVER['HTTP_ACCEPT'], $v)) {
                    return $key;
                }
            }
        }
        return false;
    }

    /**
     * 获取当前请求的请求方法信息
     * @return string
     */
    private function getRequestMethod() {
        return \strtolower($_SERVER['REQUEST_METHOD']);
    }
    
/************************************************************************/
    
    public function doException(Exception $exception){
        $data['msg'] = $exception->getMessage();
        if(DEBUG) 
            $data['exception'] = $exception->getTraceAsString();
        
        $this->sendHttpHeader(500);
        
        echo '<pre>';
        foreach($data as $v){
            echo $v;
            echo '<br>';
            echo '<br>';
        }
        exit('die');
    }
}
