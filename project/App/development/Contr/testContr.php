<?php
// 开发, 测试, demo 功能3合1
namespace App\development\Contr;
use \Main\Core\Controller;
//use \Main\Core\F;
defined('IN_SYS') || exit('ACC Denied');
    
class testContr extends Controller\HttpController {
    
    public function route(){
        echo 'this is route';
    }
    
    public function __destruct() {
        \statistic();
    }
}
