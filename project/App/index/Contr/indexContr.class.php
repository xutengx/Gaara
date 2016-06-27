<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends Controller\HttpController{
    private $user;
    public function construct(){
        $this->user = obj('userObj')->init('userModel');
    }
    public function indexDo(){
        $nonceStr = 'qbtest';
        $auth = obj('\Main\Expand\Wechat',true, APPID, APPSECRET);
        $signature = $auth->get_signature($nonceStr);
        $addrSign = $auth->get_addrSign($nonceStr);

        $this->assign('appId', APPID);
        $this->assign('timestamp', $_SERVER['REQUEST_TIME']);
        $this->assign('nonceStr', $nonceStr);
        $this->assign('signature', $signature);
        $this->assign('addrSign', $addrSign);
        $this->display();
    }
}