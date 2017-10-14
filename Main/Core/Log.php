<?php

declare(strict_types = 1);
namespace Main\Core;

use Tool;
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
    private $path = 'data/log/';
    private $ext = 'log';

    private $handle;
    
    public function __construct($name = 'gaara_', array $handlers = array(), array $processors = array()) {
        $this->handle = new Logger($name, $handlers, $processors);
        $this->handle->pushHandler(new StreamHandler($this->makeFilename('debug'), Logger::DEBUG, false));
        $this->handle->pushHandler(new StreamHandler($this->makeFilename('info'), Logger::INFO, false));
        $this->handle->pushHandler(new StreamHandler($this->makeFilename('notice'), Logger::NOTICE, false));
        $this->handle->pushHandler(new StreamHandler($this->makeFilename('warning'), Logger::WARNING, false));
        $this->handle->pushHandler(new StreamHandler($this->makeFilename('error'), Logger::ERROR, false));
        $this->handle->pushHandler(new StreamHandler($this->makeFilename('critical'), Logger::CRITICAL, false));
        $this->handle->pushHandler(new StreamHandler($this->makeFilename('emergency'), Logger::EMERGENCY, false));
    }
  

    /**
     * 返回文件名
     * @return string
     */
    private function makeFilename(string $level): string{
        $filename = ROOT.$this->path.date('Y/m/d/').$level.'.'.$this->ext;
        return $filename;
    }
    
    public function __call(string $func, array $params){
        return call_user_func_array([$this->handle, $func], $params);
    }
}
