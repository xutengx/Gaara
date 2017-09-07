<?php

declare(strict_types = 1);
namespace Main\Core;
defined('IN_SYS') || exit('ACC Denied');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log {

    const LOGDIR = 'data/dblog/curr.log';                //文件路径
    
    private $handle;
    
    public function __construct($name = 'name', array $handlers = array(), array $processors = array()) {
        $this->handle = new Logger($name, $handlers, $processors);
        $this->handle->pushHandler(new StreamHandler(self::LOGDIR, Logger::DEBUG));
    }
  
    public function __call(string $func, array $params){
        return call_user_func_array([$this->handle, $func], $params);
    }
}
