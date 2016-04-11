<?php
namespace index;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends \Business\businessController{
    public $phparray = 11231231;
    public function indexDo(){
        $this->display('test');
    }
    public function test(){
        $data = $this->put();
//        var_dump($data);
//        var_dump($_FILES);
        $this->returnData($data);
    }
    private function dddd(){
        $data = $this->get();
        $this->caheBegin('',2);

        echo '缓存 '.date('Ymd H:i:s',time()).'</br>';
        $this->returnData($data);

        $this->caheEnd();
    }
    public function cc(){
        echo 'i am cc';
        $this->cacheClear();
    }
}