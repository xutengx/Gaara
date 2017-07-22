<?php

namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class IndexContr extends Controller\HttpController {
    private $save_url = 'data/upload/';

    public function construct() {
        $this->save_url = ROOT . $this->save_url;
//        echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/git/lights_app/index.php">检测lights项目</a>';
    }

    public function indexDo() {
        headerTo('development/index/indexDo/');
//        obj('development/indexContr')->upload();
//        headerTo('development/index/indexDo/'); // 开发开始
    }
    
//    public function __destruct() {
//        \statistic();
//    }
}
