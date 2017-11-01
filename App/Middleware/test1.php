<?php

namespace App\Middleware;

use Gaara\Core\Middleware;
use \Gaara\Core\Request;
defined('IN_SYS') || exit('ACC Denied');

/**
 * 权限
 */
class test1 extends Middleware {

    public function handle() {
        echo 'this is test1 handle <br>';
    }
    public function terminate($response) {
//        echo 76;
        echo 'this is test1 terminate <br>';
//        return $response;
    }
}
