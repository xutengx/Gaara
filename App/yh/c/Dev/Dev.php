<?php

declare(strict_types = 1);
namespace App\yh\c\Dev;
defined('IN_SYS') || exit('ACC Denied');

use Main\Core\Controller\HttpController;
use Log;
//use Main\Core\PhpConsole;
use \PhpConsole;
/**
 * 调试接口用
 */
class Dev extends HttpController {
    
    protected $view = 'App/yh/c/Dev/';
    
    protected $language = 1;

    public function index() {
//        return obj(\App\yh\m\UserMerchant::class)->getRow();
        $this->assignPhp('url', url(''));
        $this->assign('test', 'this is test string !');
        return $this->display('demo');
    }
}
