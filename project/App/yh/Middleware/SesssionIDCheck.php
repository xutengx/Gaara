<?php

declare(strict_types = 1);
namespace App\yh\Middleware;
defined('IN_SYS') || exit('ACC Denied');

use Main\Core\Middleware;
use \Main\Core\Request;

/**
 * 权限
 */
class SesssionIDCheck extends Middleware {

    private $sessionID = null;
    
    public function handle(Request $request) {
        $paramArr = $request->{$request->method};
        $this->sessionID = $paramArr['sessionID'];
        $pararJson = \json_encode($paramArr);
    }
}
