<?php

namespace App\jurisdiction\Contr;
use \App\jurisdiction\jurisdictionContr;
defined('IN_SYS') || exit('ACC Denied');

class disable extends jurisdictionContr {
    public function index(){
        return '这个控制器, 你如果访问到, 说明权限控制没器什么作用';
    }
    
    
}
