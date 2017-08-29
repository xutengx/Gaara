<?php

namespace Main\Core;
defined('IN_SYS') || exit('ACC Denied');

use Workerman\Worker;

//require_once 'Autoloader.php';
//
//// 创建一个Worker监听2345端口，使用http协议通讯
//$http_worker = new Worker("http://0.0.0.0:2345");
//
//// 启动4个进程对外提供服务
//$http_worker->count = 4;
//// 接收到浏览器发送的数据时回复hello world给浏览器
//$http_worker->onMessage = function($connection, $data)
//{
//    // 向浏览器发送hello world
//    $connection->send('<h1>wqqweqwewqwqeq</h1>');
//};
// 运行worker

class Workerman {

    const logDir = 'data/workerman/';

    protected $worker = null;

    final public function __construct($socket_name = 'http://0.0.0.0:2345', $count = 1) {
        $this->worker = new \Workerman\Worker($socket_name);
        // 启动$count个进程对外提供服务
        $this->worker->count = $count;

        $this->set();
    }

    final protected function set() {
        if (!is_dir(ROOT . self::logDir))
            obj('\Main\Core\Tool')->__mkdir(ROOT . self::logDir);
        Worker::$logFile = ROOT . self::logDir . 'run.log';
        Worker::$stdoutFile = ROOT . self::logDir . 'varDump.log';
        Worker::$pidFile = ROOT . self::logDir . 'workerman.pid';
    }

    final public function runAll() {
        Worker::runAll();
    }

    final public function __set($propertyName, $value) {
        $this->worker->$propertyName = $value;
    }

    final public function __get($propertyName) {
        return $this->worker->$propertyName;
    }
}
