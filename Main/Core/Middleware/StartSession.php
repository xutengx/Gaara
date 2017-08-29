<?php

namespace Main\Core\Middleware;

use Main\Core\Middleware;
use \Main\Core\Request;
defined('IN_SYS') || exit('ACC Denied');

/**
 * 开启 session
 */
class StartSession extends Middleware {

    protected $except = []; 
    
    public function handle() {
        obj('session');
    }
}
