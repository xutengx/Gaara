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
        $this->assignPhp('url', url(''));
        $this->assign('test', 'this is test string !');
//        ob_start();
//        PhpConsole::debug(url(''));
//        var_dump(ob_get_level());
//        PhpConsole::debug(url('').'sssss');
//        PhpConsole::debug(url(''));
//        var_dump(ob_get_level());exit;
        PhpConsole::debug(123123213);
        echo 'after PhpConsole';
//        echo $this->display('demo');
//        return 'wwwwww';
        return $this->display('demo');
    }
}
