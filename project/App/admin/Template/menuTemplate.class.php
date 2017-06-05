<?php

namespace App\admin\Template;
defined('IN_SYS') || exit('ACC Denied');

class menuTemplate {
    public function getCate() {
        
        obj('categoryModel')->get_children_by_pid();
        
    }
}