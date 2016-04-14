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
//        $oldname = $this->me->name;
//        $this->me->name = 'zhangsangwwsssw';
//        var_dump($oldname);
//        var_dump($this->me->save());
//        var_dump($this->me->name);
        $this->display('test');
    }
    public function submitData(){
        $this->returnData(obj('userModule')->modifyData($this->post()));
    }
}