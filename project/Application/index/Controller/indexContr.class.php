<?php
namespace index;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends \Business\businessController{
    private $me = null;
    public function construct(){
        $this->me = obj('userObj')->init(obj('userModule'), 1);
    }
    public function indexDo(){
        $this->display();
    }
    protected function test(){
        $aa =  'echo缓存 : '.date('H:i:s',time());
        echo $aa;
//        echo $e;
//        echo 123123123;
//        return date('H:i:s',time());
    }
    public function submitData(){
        $data = ($this->post());
        sleep(1);
        $this->returnMsg(1,'w');exit;
        $this->returnData(obj('userModule')->modifyData($this->post()));
    }
}