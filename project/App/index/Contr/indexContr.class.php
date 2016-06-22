<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends Controller\HttpController{
    private $user;
    public function construct(){
        $this->user = obj('userObj')->init('userModel');
    }
//    public function indexDo(){
//        unset($_SESSION['openid']);
//        echo 'index';
//
//        $ff = $this->sign([
//            'page_no'=>1,
//            'page_size'=>10,
//            'sessionId'=>"OJOi0/KQzTaGk8s2eJ7PJSXfZYUBm1T1",
//
//        ],'suneee');
//
//        var_dump($ff);
//    }
//    function sign($params,$key){
//        if(empty($params) || empty($key)){
//            return false;
//        }
//        if(is_array($params)){
//            $str = http_build_query($params);
//            //秘钥组合组合式md5签名
//            return md5($str);
//            $str = md5(md5($str).$key);
//        }
//        return $str;
//    }
}