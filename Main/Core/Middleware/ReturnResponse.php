<?php

declare(strict_types = 1);
namespace Main\Core\Middleware;
defined('IN_SYS') || exit('ACC Denied');

use Main\Core\Middleware;
use Response;
use PhpConsole;

/**
 * 移除意外输出, 使用PhpConsole调试
 */
class ReturnResponse extends Middleware {

    protected $except = [];

    public function handle() {
        ob_start();
    }

    public function terminate($response) {
        $content = ob_get_contents();
        ob_end_clean();
        if(!empty($content))
            PhpConsole::debug ($content);
        Response::exitData($response);
    }
}
