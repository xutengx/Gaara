<?php

namespace Main\Core\Middleware;

use Main\Core\Middleware;
use \Main\Core\Request;
defined('IN_SYS') || exit('ACC Denied');

/**
 * 开启 session
 */
class StartSession extends Middleware {

    public function __invoke(Request $request) {
        obj('session');
    }
}
