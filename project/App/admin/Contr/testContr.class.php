<?php

namespace App\admin\Contr;
use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class testContr extends Controller\HttpController {
    
    public function indexDo() {
        $re = obj('adminModel')->data(['ip'=>2])->where(['id'=>1])->update();
        var_dump($re);exit;
        
    }
}