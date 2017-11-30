<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Gaara\Core\Response\Traits;
use Gaara\Core\Conf;
use PhpConsole;
/**
 * 处理系统全部响应( 输出 )
 */
class Response {
    
    use Traits\SetTrait;
    
    private static $httpStatus = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',            // RFC2518
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',          // RFC4918
        208 => 'Already Reported',      // RFC5842
        226 => 'IM Used',               // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',    // RFC7238
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
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                                               // RFC2324
        421 => 'Misdirected Request',                                         // RFC7540
        422 => 'Unprocessable Entity',                                        // RFC4918
        423 => 'Locked',                                                      // RFC4918
        424 => 'Failed Dependency',                                           // RFC4918
        425 => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
        426 => 'Upgrade Required',                                            // RFC2817
        428 => 'Precondition Required',                                       // RFC6585
        429 => 'Too Many Requests',                                           // RFC6585
        431 => 'Request Header Fields Too Large',                             // RFC6585
        451 => 'Unavailable For Legal Reasons',                               // RFC7725
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',                                     // RFC2295
        507 => 'Insufficient Storage',                                        // RFC4918
        508 => 'Loop Detected',                                               // RFC5842
        510 => 'Not Extended',                                                // RFC2774
        511 => 'Network Authentication Required',                             // RFC6585
    );
    private static $httpType = [
        'html' => ['text/html', 'application/xhtml+xml'],
        'php' => ['application/php', 'text/php', 'php'],
        'xml' => ['application/xml', 'text/xml', 'application/x-xml'],
        'json' => ['application/json', 'text/x-json', 'application/jsonrequest', 'text/json','text/javascript'],
        'js' => ['text/javascript', 'application/javascript', 'application/x-javascript'],
        'css' => ['text/css'],
        'rss' => ['application/rss+xml'],
        'yaml' => ['application/x-yaml,text/yaml'],
        'atom' => ['application/atom+xml'],
        'pdf' => ['application/pdf'],
        'text' => ['text/plain'],
        'png' => ['image/png'],
        'jpg' => ['image/jpg,image/jpeg,image/pjpeg'],
        'gif' => ['image/gif'],
        'csv' => ['text/csv'],
    ];
    // 当前请求类型
    private $requestMethod = '';
    // 当前请求的资源类型
    private $acceptType = '';
    // 响应的字符编码
    private $char = null;
    // 响应状态码
    private $status = null;
    // 响应文档格式
    private $contentType = null;
    // 是否已经编码(encode)
    private $encode = false;
    // cli模式下响应存放
    private $data = null;

    public function __construct() {
        $this->acceptType = $this->getAcceptType();
        $this->requestMethod = $this->getRequestMethod();
        $this->char = obj(Conf::class)->app['char'];
    }
    
    /**
     * 终止进程并响应内容, 可通过set方法设置状态码等
     * @param mix $data
     * @return void
     */
    public function exitData($data = ''): void {
        $content = ob_get_contents();
        if(ob_get_length() > 0){
            PhpConsole::debug ($content, 'Unexpected output');
        }
        ob_end_clean();
        if(CLI){
            $this->setData($this->response($data));
        }else{
            exit($this->response($data));
        }
    }
    
    /**
     * 返回响应内容, 可通过set方法设置状态码等
     * @param type $data
     * @return string
     */
    public function returnData($data = ''): string {
        return $this->response($data);
    }
    
    /**
     * 返回页面
     * @param string $file
     * @return string
     */
    public function view(string $file): string{
        $data = obj(Template::class)->view($file);
        return $this->setContentType('html')->response($data);
    }
    
    /**
     * cli模式下 设置返回值
     * @param string $data
     */
    private function setData(string $data): void{
        $this->data = $data;
    }
    
    /**
     * cli模式下 获取返回值
     * @return string
     */
    public function getData(): string{
        return $this->data;
    }
    
    /**
     * 编码响应内容,设置状态码
     * @param type $data
     * @return string
     */
    private function response($data): string{
        return $this->setStatus(200)->setContentType($this->acceptType)->encodeData($data);
    }

    /**
     * 编码响应内容
     * @param mixed  $data 要返回的数据
     * @return string
     */
    private function encodeData($data = ''): string {
        if ($this->encode === true){
            return is_null($data) ? '' : (string)$data;
        }
        $this->encode = true;
        $encode = '';
        switch ($this->contentType) {
            case 'json':
                $encode = json_encode($data, JSON_UNESCAPED_UNICODE);
                break;
            case 'xml':
                $encode = obj(Tool::class)->xml_encode($data, $this->char);
                break;
            case 'php':
                $encode = serialize($data);
                break;
            case 'html':
                $encode = is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data;
                break;
            default:
                $encode = $data;
        }
        return (string)$encode;
    }

    /**
     * 获取当前请求的Accept头信息
     * @return string
     */
    private function getAcceptType(): string {
        if(isset($_SERVER['HTTP_ACCEPT'])){
            foreach (self::$httpType as $key => $val) {
                foreach ($val as $v) {
                    if (stristr($_SERVER['HTTP_ACCEPT'], $v)) {
                        return $key;
                    }
                }
            }
        }
        return 'html';
    }

    /**
     * 获取当前请求的请求方法信息
     * @return string
     */
    private function getRequestMethod(): string {
        return \strtolower($_SERVER['REQUEST_METHOD']);
    }

}
