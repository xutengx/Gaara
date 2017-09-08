<?php

declare(strict_types = 1);
namespace Main\Core;
defined('IN_SYS') || exit('ACC Denied');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * 记录日志
 * @methor debug        (string, array());
 * @methor info         (string, array());
 * @methor notice       (string, array());
 * @methor warning      (string, array());
 * @methor critical     (string, array());
 * @methor alert        (string, array());
 * @methor emergency    (string, array());
 */
class Log {
    //文件路径
    const LOGDIR = 'data/log/curr.log';
    
    private $handle;
    
    public function __construct($name = 'php_', array $handlers = array(), array $processors = array()) {
        $this->handle = new Logger($name, $handlers, $processors);
        $this->handle->pushHandler(new StreamHandler(self::LOGDIR, Logger::DEBUG));
    }
  
    public function __call(string $func, array $params){
        return call_user_func_array([$this->handle, $func], $params);
    }
}
