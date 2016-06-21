<?php
namespace App\index\Obj;
defined('IN_SYS')||exit('ACC Denied');
class userObj extends \Main\Core\Object{
    protected function bind(){
        if(isset($_SESSION['user'])){
            $user['time'] = isset($_SESSION['user']['time']) ? $_SESSION['user']['time'] : 0;
            $user['tel']  = isset($_SESSION['user']['tel']) ? $_SESSION['user']['tel'] : 0;
            $user['openid']  = isset($_SESSION['user']['openid']) ? $_SESSION['user']['openid'] : '';
            if($user['tel']===0 && $user['openid']==='' ) return false;

            $re = obj('userModel')->where($user)->getRow();
            return isset($re['id']) ? $re : false;
        }
        return false;
    }
    protected function bindFalse(){
        headerTo('index/nologin/indexDo/');
    }
}