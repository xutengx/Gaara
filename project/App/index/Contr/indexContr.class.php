<?php

namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class indexContr extends Controller\HttpController {

    private $save_url = 'data/upload/';

//     // 成功状态统一
//    private $success_status = [
//        '1',1,'success','SUCCESS','true','TRUE',true
//    ];
    public function construct() {
        $this->save_url = ROOT . $this->save_url;
        echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/git/lights_app/index.php">检测lights项目</a>';
    }

    public function indexDo() {
        $r = asynExe('index/index/test',['ttt'=>'tt','id'=>2]);
        var_dump($r);
        exit;
    }

    public function test() {
        $y = $this->get('ttt');
        sleep(5);
        obj('Log')->write($y);
    }

    public function __destruct() {
        \statistic();
    }
}