<?php

declare(strict_types = 1);
namespace Main\Core\Middleware;
defined('IN_SYS') || exit('ACC Denied');

use Main\Core\Middleware;
use Main\Core\PhpConsole;
use Response;
//use PhpConsole;

/**
 * 移除意外输出, 使用PhpConsole调试
 */
class ReturnResponse extends Middleware {

    protected $except = [];
    /**
     * 初始化 PhpConsole, 其__construct 中启用了ob_start
     * @param PhpConsole $PhpConsole
     */
//    public function handle(PhpConsole $PhpConsole) {
//        
//    }

    public function terminate($response, PhpConsole $PhpConsole) {
        echo $response;
//        Response::exitData($response);
    }
}
