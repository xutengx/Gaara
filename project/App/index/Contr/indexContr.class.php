<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends Controller\HttpController{
    private $user;
    public function construct(){
//        $this->user = obj('userObj')->init('userModel');
    }
    public function indexDo(){
//        $nonceStr = 'qbtest';
//        $auth = obj('\Main\Expand\Wechat',true, APPID, APPSECRET);
//        $signature = $auth->get_signature($nonceStr);
//        $addrSign = $auth->get_addrSign($nonceStr);
//
//        $this->assign('appId', APPID);
//        $this->assign('timestamp', $_SERVER['REQUEST_TIME']);
//        $this->assign('nonceStr', $nonceStr);
//        $this->assign('signature', $signature);
//        $this->assign('addrSign', $addrSign);
//        $this->display();
//        var_dump(( extension_loaded('pdo') ) );
//        phpinfo();
//        var_dump(obj('cache'));exit;
//        obj('cache')->call($this,'tt',1,$this);
//        $r = obj('cache')->set('ff','wwww');
//        var_dump($r);
//        obj('cache')->get('ff',function(){
//            return $this->tt('wwwww2222wwwwww');
//        });
//        $re = obj('cache')->get('ff');

//        $re = obj('cache')->call($this,'tt',true,222222);
//        var_dump($re);

        obj('cache')->clear($this,'tt',222222);

    }
    public function tt($r){
        $rr = $r+999123212;
        return $rr;
//            $nonceStr = 'qbtest';
//            $auth = obj('\Main\Expand\Wechat',true, APPID, APPSECRET);
//            $signature = $auth->get_signature($nonceStr);
//            $addrSign = $auth->get_addrSign($nonceStr);
//
//            $this->assign('appId', APPID);
//            $this->assign('timestamp', $_SERVER['REQUEST_TIME']);
//            $this->assign('nonceStr', $nonceStr);
//            $this->assign('signature', $signature);
//            $this->assign('addrSign', $addrSign);
//            $this->display();
    }
}