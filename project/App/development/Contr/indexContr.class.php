<?php
// 开发, 测试, demo 功能3合1
namespace App\development\Contr;
use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class indexContr extends Controller\HttpController {
    public function indexDo() {
//        $splq = new \SplStack;
//        
//        $splq->push(1);
//        $splq->push(2);
//        $splq->push(3);
//        $splq->push(4);
//        
//        var_dump($splq->pop());
//        var_dump($splq->pop());
//        var_dump($splq->shift());
//        var_dump($splq->shift());
        $arr[] = 1;
        $arr[] = 2;
        $arr[3] = 4;
        $arr[4] = 3;
        
        $tt = [];
        array_push($tt, 1);
        array_push($tt, 2);
        array_push($tt, 4);
        array_push($tt, 3);
        
        var_dump($arr);
        var_dump($tt);
        
    }
    

   
    public function __destruct() {
        \statistic();
    }
}
