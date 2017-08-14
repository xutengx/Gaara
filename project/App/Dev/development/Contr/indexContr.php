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

    public function indexDo(Request $request, $test, \Main\Core\Cache $c , \HTMLPurifier $htmlpurifier) {

//        var_dump(func_get_args());exit;
////        var_dump($request);
        var_dump($request->get('test'));
        var_dump(htmlspecialchars($_GET['test']));
        exit;
//        Cache::set('www','www1',4);
//        $e = $c->get('www');
        
//        var_dump($request);exit;
        var_dump($purifier->purify($dirty_html));
        
        
        exit;
        
        $sql = Model\visitorInfoModel::select(['id', 'name', 'phone'])
            ->where([ 'id' => [ '>', '101' ]])
            ->where(['id' => ['<', '104']])
            ->getAll();
        var_dump($sql);
        exit;

        
        echo 'qewq';
//        $this->display();
    }

    private function test($request) {
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
