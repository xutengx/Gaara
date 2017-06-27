<?php
// 开发, 测试, demo 功能3合1
namespace App\development\Contr;
use \Main\Core\Controller;
//use \Main\Core\F;
defined('IN_SYS') || exit('ACC Denied');
    
class indexContr extends Controller\HttpController {
    public function indexDo() {
        echo "<pre>";
        var_dump(['123',432,'www']);
//        $this->returnData('eee');
    }
    
    public function test($request){
        // 'account' => '/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/',
        var_dump($request->get);
        var_dump($this->get());
        exit;
        exit('test');
        
    }


    public function __destruct() {
//        \statistic();
    }
}
