<?php

declare(strict_types = 1);
namespace Main\Core;
defined('IN_SYS') || exit('ACC Denied');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * 记录日志
 * @methor debug        100     (string, array());
 * @methor info         200     (string, array());
 * @methor notice       250     (string, array());
 * @methor warning      300     (string, array());
 * @methor error        400     (string, array());
 * @methor critical     500     (string, array());
 * @methor alert        550     (string, array());
 * @methor emergency    600     (string, array());
 */
class Log {
    // 文件路径
    const LOGDIR = 'data/log/';

    private $handle;
    
    public function __construct($name = 'php_', array $handlers = array(), array $processors = array()) {
        $this->handle = new Logger($name, $handlers, $processors);
        $this->handle->pushHandler(new StreamHandler(self::LOGDIR.'debug.log', Logger::DEBUG, false));
        $this->handle->pushHandler(new StreamHandler(self::LOGDIR.'info.log', Logger::INFO, false));
        $this->handle->pushHandler(new StreamHandler(self::LOGDIR.'notice.log', Logger::NOTICE, false));
        $this->handle->pushHandler(new StreamHandler(self::LOGDIR.'warning.log', Logger::WARNING, false));
        $this->handle->pushHandler(new StreamHandler(self::LOGDIR.'error.log', Logger::ERROR, false));
        $this->handle->pushHandler(new StreamHandler(self::LOGDIR.'critical.log', Logger::CRITICAL, false));
        $this->handle->pushHandler(new StreamHandler(self::LOGDIR.'emergency.log', Logger::EMERGENCY, false));
    }
  
    public function __call(string $func, array $params){
        return call_user_func_array([$this->handle, $func], $params);
    }
}
