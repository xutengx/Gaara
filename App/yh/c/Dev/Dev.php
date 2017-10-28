<?php

declare(strict_types = 1);
namespace App\yh\c\Dev;

use Main\Core\Controller\HttpController;
use Log;
//use Main\Core\PhpConsole;
use \PhpConsole;
/**
 * 调试接口用
 */
class Dev extends HttpController {
    
    use \Main\Core\Controller\Traits\RequestTrait;

    protected $view = 'App/yh/c/Dev/view/';
    
    protected $language = 0;

    public function index() {
        
        $this->js('js/test.js');
        
        $this->js('http://cdn.bootcss.com/blueimp-md5/1.1.0/js/md5.min.js');
        
        $this->title('dev');
        
        
//        return obj(\App\yh\m\UserMerchant::class)->getRow();
        $this->assignPhp('url', url(''));
        $this->assign('test', 'this is test string !');
        return $this->display();
    }
}
