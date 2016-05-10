<?php
namespace admin;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends \Business\businessController{
    public function indexDo(){
        $this->assignPhp('admin','true');
        $this->display();
    }
    public function test(){
        $data = $this->post();
//        var_dump($data);
//        var_dump($_FILES);
        $this->returnData($data);

    }
    public function make(){
        return obj('admin\userModule');
    }
}