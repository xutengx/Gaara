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
        
        $sql = Model\visitorInfoModel::select(['id', 'name', 'phone'])
            ->where([ 'id' => [ '>', '101' ]])
            ->where(['id' => ['<', '104']])
            ->getAll();
        var_dump($sql);
        exit;

        
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
