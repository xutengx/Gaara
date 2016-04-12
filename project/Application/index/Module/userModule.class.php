<?php
namespace index;
defined('IN_SYS')||exit('ACC Denied');
class userModule extends \Business\businessModule{
    public function test(){
        $arr = func_get_args();
        return array('pars'=> $arr,'test'=>444,'arr'=>array('q'=>'q','w'=>'iii1iii'));
    }
}