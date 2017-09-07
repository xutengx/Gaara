<?php

declare(strict_types = 1);
namespace App\yh\c\Dev;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m\MainUser;
use Main\Core\Controller\HttpController;
use Main\Core\Request;
use App\yh\s\Token;
use Main\Core\Log;
/**
 * 调试接口用
 */
class Dev extends HttpController {
    
    protected $view = 'App/yh/c/Dev/';
    
    protected $language = 1;

    public function index(Log $Log) {
        $Log->error('' ,['ww','qwe']);
        
        $this->assignPhp('url', url(''));
        $this->assign('test', 'this is test string !');

        $this->display('demo');
    }
}
