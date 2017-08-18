<?php

namespace Main\Core\Middleware;

use Main\Core\Middleware;
use \Main\Core\Request;
defined('IN_SYS') || exit('ACC Denied');

/**
 * 根据http协议返回
 */
class ReturnResponse extends Middleware {

    protected $except = [];
    

    public function handle() {
        ob_start();
    }
    
    
    public function terminate($response) {
        $content = ob_get_contents();
        ob_end_clean(); 
        echo $content;
        if($response){
            echo $response;
        }
    }
}