<?php
namespace Main\Core\Controller\Module;
defined('IN_SYS')||exit('ACC Denied');
trait RestModule{
    // 当前请求类型
    protected $_method = '';
    // 当前请求的资源类型
    protected $_type = '';
    // REST允许的请求类型列表
    protected $allowMethod = array('get', 'post', 'put', 'delete');
    // REST允许请求的资源类型列表
    protected $allowType = array('html', 'xml', 'json', 'php');
    // 默认的资源类型
    protected $defaultType = 'html';
    // REST允许输出的资源类型列表
    protected $allowOutputType = array('xml' => 'application/xml', 'json' => 'application/json', 'html' => 'text/html',);

    /**
     * 架构函数
     * @access public
     */
    public function RestModuleConstruct(){
        // url上的请求类型 检测
        if( !empty($_SERVER['PATH_INFO']) )
            $this->_type = strtolower(pathinfo($_SERVER['PATH_INFO'], 4));
        // header上的请求类型 检测
        else $this->_type = $this->getAcceptType();
        if(!in_array($this->_type, $this->allowType))
            // 资源类型非法 则用默认资源类型访问
            $this->_type = $this->defaultType;
        // 请求方式检测
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        if(!in_array($method, $this->allowMethod))
            $this->returnData('error','json',405);
        $this->_method = $method;
        $this->{$method}(obj('f')->{$method});
    }
    public function indexDo(){

    }
    abstract public function get( $data);
    abstract public function put( $data);
    abstract public function post( $data);
    abstract public function delete( $data);

    /**
     * 获取当前请求的Accept头信息
     * @return string
     */
    protected function getAcceptType(){
        $type = array('html' => 'text/html,application/xhtml+xml,*/*', 'xml' => 'application/xml,text/xml,application/x-xml', 'json' => 'application/json,text/x-json,application/jsonrequest,text/json', 'js' => 'text/javascript,application/javascript,application/x-javascript', 'css' => 'text/css', 'rss' => 'application/rss+xml', 'yaml' => 'application/x-yaml,text/yaml', 'atom' => 'application/atom+xml', 'pdf' => 'application/pdf', 'text' => 'text/plain', 'png' => 'image/png', 'jpg' => 'image/jpg,image/jpeg,image/pjpeg', 'gif' => 'image/gif', 'csv' => 'text/csv');
        foreach( $type as $key => $val ){
            $array = explode(',', $val);
            foreach( $array as $k => $v ){
                if(stristr($_SERVER['HTTP_ACCEPT'], $v)){
                    return $key;
                }
            }
        }
        return false;
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
    protected function returnData($data, $type = false, $code = 200){
        $type = $type ? strtolower($type) : $this->_type;
        $this->sendHttpHeader($code, $type);
        exit($this->encodeData($data, $type));
    }
    // 发送Http状态信息
    protected function sendHttpHeader($code=200, $type=false){
        static $_status = array(// Informational 1xx
            100 => 'Continue', 101 => 'Switching Protocols', // Success 2xx
            200 => 'OK', 201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', // Redirection 3xx
            300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Moved Temporarily ',  // 1.1
            303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', // 306 is deprecated but reserved
            307 => 'Temporary Redirect', // Client Error 4xx
            400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed', // Server Error 5xx
            500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported', 509 => 'Bandwidth Limit Exceeded');
        if(isset($_status[ $code ]) && !headers_sent()){
            header('HTTP/1.1 '.$code.' '.$_status[ $code ]);
            // 确保FastCGI模式下正常
            header('Status:'.$code.' '.$_status[ $code ]);
            $charset = !empty($charset) ? $charset : obj('conf')->char;
            if(isset($this->allowOutputType[ strtolower($type) ]))
                header('Content-Type: '.$this->allowOutputType[ $type ].'; charset='.$charset);
        }
    }
    /**
     * 编码数据
     * @access protected
     * @param mixed  $data 要返回的数据
     * @param String $type 返回类型 JSON XML
     *
     * @return string
     */
    protected function encodeData($data='', $type = 'json'){
        switch($type){
            case 'json':
                return json_encode($data);
            case 'xml':
                return obj('tool')->xml_encode($data);
            case 'php':
                return serialize($data);
            case 'html':
                return is_array($data) ? json_encode($data) : $data;
            default:
                return var_export($data);
        }
    }
}