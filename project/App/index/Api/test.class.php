<?php
namespace App\index\Api;
defined('IN_SYS')||exit('ACC Denied');
class test extends \Main\Core\Controller{
    private $me = null;
    public function construct(){
//        $this->me = obj('userObj')->init(obj('userModule'), 1);
//        $this->me = obj('cache')->cacheCall(obj('userObj'),'init',true,obj('userModule'), 1);
    }
    public function indexDo(){

    }
    public function ttt(){
        obj('cache')->cacheClear();
    }
//    protected function test($w=0){
//        $re = obj('userModule');
////        for($i=0 ; $i<3999 ; $i++){
////            obj('userModule')->selRow(1);
////        }
////        $aa =  'echo缓存 : '.date('H:i:s',time());
////        echo $aa;
//        return $re;
//    }
    public function submitData(){
        $data = ($this->post());
        sleep(1);
        $this->returnMsg(1,'w');exit;
        $this->returnData(obj('userModule')->modifyData($this->post()));
    }
    public function tes(){
        return 123123123;
    }
}