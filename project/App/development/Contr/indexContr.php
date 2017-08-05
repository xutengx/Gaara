<?php

// 开发, 测试, demo 功能3合1
namespace App\development\Contr;

use \Main\Core\Controller\HttpController;
use App\development\Dev\test;
use App\development\Dev\asyncDev;
use App\development\Model;
use Cache;
use Request;

defined('IN_SYS') || exit('ACC Denied');
class indexContr extends HttpController {

    public function indexDo( $request) {
//        var_dump($request);
//        var_dump(Request::$get);
//        exit;
//        $obj = obj(Model\visitorInfoModel::class);
//        
//        $sql = $obj->select(['id', 'name', 'phone'])
//            ->where([ 'id' => [ '>', '101' ]])
//            ->where(['id' => ['<', '104']])
//            ->getAll(false);
//        var_dump($sql);
//        exit;
        Cache::set('rrr', '123123');
        var_dump(Cache::get('rrr'));
        exit;
        $a = obj(Cache::class);
        $b = obj('cache');
        var_dump($a === $b);
        var_dump($a );
        var_dump($b);
        exit;
        
        $b = obj('cache')->get('ttt');
        
        var_dump($b);
        exit;
        
        (new test)->test();
        (new test)->testStatic();
         test::test();
         test::testStatic();
         
        (new asyncDev)->test();
        (new asyncDev)->testStatic();
         asyncDev::test();
         asyncDev::testStatic();
        
        
        echo 'qewq';
//        $this->display();
    }

    public function test($request) {
        // 'account' => '/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/',
        var_dump($request->get);
        var_dump($this->get());
        exit;
        exit('test');
    }

    public function tt() {
        echo 'qweqwe';
    }

    public function __destruct() {
        \statistic();
    }
}
