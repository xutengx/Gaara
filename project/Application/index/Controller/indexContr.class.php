<?php
namespace index;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends \Business\businessController{
    private $me = null;
    public function construct(){
        $this->me = obj('userObj')->init(obj('userModule'), 1);
    }
    public function indexDo(){
//        var_dump($this->me);
//var_dump(function_exists('obj'));
//        echo 'hello World !';
//        obj('cache')->cacheCall($this,'test',true);
//        obj('cache')->cacheCall($this,'test',true,1234);
//        $this->display('index2');
        statistic();
    }
    public function ttt(){
        obj('cache')->cacheClear();
    }
    protected function test($w=0){
        for($i=0 ; $i<3999 ; $i++){
            obj('userModule')->selRow(1);
        }
        $aa =  'echo缓存 : '.date('H:i:s',time());
        echo $aa;
    }
    public function submitData(){
        $data = ($this->post());
        sleep(1);
        $this->returnMsg(1,'w');exit;
        $this->returnData(obj('userModule')->modifyData($this->post()));
    }
}