<?php
namespace index;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends \Business\businessController{
    public function indexDo(){
        $t1 = obj('userModule');
        $t2 = obj('userObj');
        $this->display('test');
    }
    public function test(){
        $data = $this->post();
//        var_dump($data);
//        var_dump($_FILES);
        $this->returnData($data);
    }
}