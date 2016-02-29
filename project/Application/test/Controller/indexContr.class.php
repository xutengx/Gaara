<?php
namespace index;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends \Main\Controller{
    public function indexDo(){
        echo '<br/>bigin<br/>';
//        $user = obj('test\t\test','true', 'rrrrrrr');
//        $user2 = obj('userModule','admin');
//        $user3 = obj('userModule','admin');
//        var_dump($user===$user2);
//        var_dump($user3===$user2);
//        $obj = obj('tttContr');
//        $obj->hello();

//        var_dump(\Main\obj::$obj);
    }
    public function test(){

    }
}

class tttContr extends \index\indexContr{
    public function hello(){
        echo 'hello world';
    }
}