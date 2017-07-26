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

    public function indexDo($a = null ,$b = null ,$c = null ,$d = null ) {
    
        var_dump($a);
        var_dump($b);
        var_dump($c);
        var_dump($d);
        var_dump(func_get_args());exit;
        
//        headerTo('development/index/indexDo/');
//        obj('development/indexContr')->upload();
//        headerTo('development/index/indexDo/'); // 开发开始
    }
    
    protected function test(int $a = 3){
        echo 'nono';
        return $a + 1;
    }


//    public function __destruct() {
//        \statistic();
//    }
}
