<?php

declare(strict_types = 1);
namespace Main\Core\Middleware;

use Main\Core\Middleware;
use Main\Expand\PhpConsole;
use Response;

/**
 * 统一响应处理
 * 移除意外输出, 使用PhpConsole调试
 */
class ReturnResponse extends Middleware {

    protected $except = [];

    /**
     * 初始化 PhpConsole, 其__construct 中启用了ob_start, 再手动启用ob_start, 确保header头不会提前发出
     * 一层ob的情况下当使用ob_end_clean关闭之后的内容若超过web_server(nginx)的输出缓冲大小(默认4k),将会被发送
     * 受限于http响应头大小,意外输出过多时(大于3000)将会全部被舍去
     * 
     * @param PhpConsole $PhpConsole
     */
    public function handle(PhpConsole $PhpConsole) {
        ob_start();
    }

    /**
     * 特殊处理 true/false
     * @param type $response
     */
    public function terminate($response) {
        if($response === true){
            Response::setStatus(200)->exitData();
        }elseif($response === false){
            Response::setStatus(400)->exitData();
        }
        Response::exitData($response);
    }
}
