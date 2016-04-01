<?php
namespace index;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends \Main\Controller{
    public function indexDo(){
        $this->base_headerTo('我要走远了!','index','index','test',array('id'=>123));
    }
    public function test(){
        $id = $this->get('id');
        var_dump(obj('userObj'));
//        echo 'success!!!!!!!'.$id;
        $this->display('test');
    }
    public function test1(){
        $data = array('state'=>1,'msg'=>'ok!!');
        sleep(5);
//        var_dump($data);
        $this->returnMsg(1,'ok!!!!!');
    }
}