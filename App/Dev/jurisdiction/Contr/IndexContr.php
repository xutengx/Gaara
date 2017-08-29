<?php

namespace App\jurisdiction\Contr;
use \App\jurisdiction\jurisdictionContr;
defined('IN_SYS') || exit('ACC Denied');

class IndexContr extends jurisdictionContr {
    public function index(){
        return 'this is 权限控制相关的控制器';
    }
    
    
}
