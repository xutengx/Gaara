<?php
// 开发, 测试, demo 功能3合1
namespace App\development\Contr;
use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class indexContr extends Controller\HttpController {
    public function indexDo() {
        $this->async_remote();
    }
    
    // 测试异步消息发送
    private function async_test(){
        obj('asyncDev')->send();
    }
    
    //
    private function async_remote(){
        $arr = [
            'http://192.168.43.128/git/php_/project/index.php?path=development/index/write_something_into_log/something/1',
            'http://192.168.43.128/git/php_/project/index.php?path=development/index/write_something_into_log/something/2',
            'http://192.168.43.128/git/php_/project/index.php?path=development/index/write_something_into_log/something/3',
            'http://192.168.43.128/git/php_/project/index.php?path=development/index/write_something_into_log/something/4',
        ];
        $re = obj('asyncDev')->remote($arr);
        var_dump($re);exit;
    }
    
    // 
    public function write_something_into_log(){
        $id = $this->get('something');
//        obj('log')->write($id.' time: '.time());
        sleep(3);
        echo 'respenson :'.$id.' time :'.time();
    }

    public function __destruct() {
        \statistic();
    }
}
