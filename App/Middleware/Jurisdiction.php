<?php

namespace App\Middleware;

use Gaara\Core\Middleware;
use \Gaara\Core\Request;
defined('IN_SYS') || exit('ACC Denied');

/**
 * 权限
 */
class Jurisdiction extends Middleware {

    public function handle(Request $request) {
//        var_dump($request);
    }
}
