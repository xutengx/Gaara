<?php

namespace App\Middleware;

use Gaara\Core\Middleware;
use \Gaara\Core\Request;
defined('IN_SYS') || exit('ACC Denied');

/**
 * 权限
 */
class test2 extends Middleware {

    public function handle() {
        echo 'this is test2 handle <br>';
    }
    public function terminate(Request $r , $response) {
//        var_dump($response);
        echo 'this is test2 terminate <br>';
        return $response.'test2';
    }
}
