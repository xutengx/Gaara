<?php

namespace Main\Core\Middleware;

use Main\Core\Middleware;
use Main\Core\Request;
use Main\Core\PhpConsole;

defined('IN_SYS') || exit('ACC Denied');

/**
 * 性能监控
 */
class PerformanceMonitoring extends Middleware {

    public function terminate($response, PhpConsole $PhpConsole) {
        $info = \statistic();
        $PhpConsole->debug($info,'PerformanceMonitoring');

        return $response;
    }
   
}
