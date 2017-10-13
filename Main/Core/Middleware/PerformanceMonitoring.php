<?php

declare(strict_types = 1);
namespace Main\Core\Middleware;

use Main\Core\Middleware;
use Main\Expand\PhpConsole;

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
