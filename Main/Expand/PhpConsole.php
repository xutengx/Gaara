<?php

declare(strict_types = 1);
namespace Main\Expand;

use Tool;
use Main\Core\Conf;
use PhpConsole\Handler;
use PhpConsole\Connector;
use PhpConsole\Storage\File;

/**
 * 借助谷歌浏览器的 php console 插件, 以及 php-console 包, 进行调试
 * @methor debug        (mix, string);
 */
class PhpConsole {

    private $path = 'data/phpconsole/';
    private $ext = 'log';
    private $handle;
    
    public function __construct() {
        $conf = obj(Conf::class)->phpconsole;
        Connector::setPostponeStorage(new File($this->makeFilename()));
        $connector = Connector::getInstance();
        if(!is_null($conf['passwd'])){
            $connector->setPassword($conf['passwd']);
        }
        $this->handle = Handler::getInstance();
    }

    /**
     * 返回文件名
     * @return string
     */
    private function makeFilename(): string{
        $filename = ROOT.$this->path.date('Y/m/d').'.'.$this->ext;
        if(!is_file($filename)){
            Tool::printInFile($filename, '');
        }
        return $filename;
    }
  
    public function __call(string $func, array $params){
        return call_user_func_array([$this->handle, $func], $params);
    }
}
