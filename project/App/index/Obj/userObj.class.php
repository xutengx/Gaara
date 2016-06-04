<?php
namespace App\index\Obj;
defined('IN_SYS')||exit('ACC Denied');
class userObj extends \Main\Core\Object{
    protected function bind(){
        session_start();
        if(isset($_SESSION['account'])){
            $re = obj('userModel')->checkUser($_SESSION['account'], $_SESSION['passwd'], $_SESSION['timeLogin']);
            session_commit();
            return $re['id'] ? $re : false;
        }
        session_commit();
        return false;
    }
    protected function bindFalse(){
//        exit(json_encode(['code'=>'0','message'=>'用户验证失败!']));
        if(CLI) return false;
        headerTo('index/login/indexDo/','请先登入!');
    }
}