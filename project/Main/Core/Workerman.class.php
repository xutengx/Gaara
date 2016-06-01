<?php
namespace Main\Core;
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

require_once ROOT.'Main/Core/Workerman/Autoloader.php';
class Workerman{
    protected $worker = null;
    final public function __construct($socket_name='http://0.0.0.0:2345', $count=1){
        $this->worker = new Worker($socket_name);
        // 启动$count个进程对外提供服务
        $this->worker->count = $count;
    }

    /**
     * 向浏览器发送hello world
     * $connection->send('<h1>hello world</h1>');
     * @param $connection
     * @param $data
     *
     * @return mixed
     */
//     protected function returnData($connection, $data){
//         $connection->send('<h1>wqqweqwewqwqeq</h1>');
//     }
//    // 当客户端连上来时
//    abstract public function handle_connection($connection);
//    // 当客户端发送消息过来时
//    abstract public function handle_message($connection, $data);
//    // 当客户端断开时，广播给所有客户端
//    abstract public function handle_close($connection);

    final public function runAll(){
        Worker::runAll();
    }
    final public function __set($propertyName, $value){
        $this->worker->$propertyName = $value;
    }
    final public function __get($propertyName){
        return $this->worker->$propertyName;
    }
}