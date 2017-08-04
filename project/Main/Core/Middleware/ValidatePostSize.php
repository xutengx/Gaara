<?php

namespace Main\Core\Middleware;

use Main\Core\Middleware;
use Main\Core\Request;
use Main\Core\Exception;

defined('IN_SYS') || exit('ACC Denied');

/**
 * 验证 post 数据大小,避免大于php设定的post_max_size
 */
class ValidatePostSize extends Middleware {

    public function handle(Request $request) {
        if ($request->CONTENT_LENGTH > $this->getPostMaxSize()) {
            throw new Exception('too large post');
        }
    }
    
    /**
     * Determine the server 'post_max_size' as bytes.
     *
     * @return int
     */
    protected function getPostMaxSize(){
        if (is_numeric($postMaxSize = ini_get('post_max_size'))) {
            return (int) $postMaxSize;
        }
        $metric = strtoupper(substr($postMaxSize, -1));
        switch ($metric) {
            case 'K':
                return (int) $postMaxSize * 1024;
            case 'M':
                return (int) $postMaxSize * 1048576;
            case 'G':
                return (int) $postMaxSize * 1073741824;
            default:
                return (int) $postMaxSize;
        }
    }
}
