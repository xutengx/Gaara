<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class telloginContr extends Controller\HttpController{
    public function login(){
        $data = $this->post();

        $info = obj('userModel')->getByTel($data['tel'], $data['passwd']);
        if(!empty($info)){
            $_SESSION['user'] = $info;
            $_SESSION['user']['time'] = obj('userModel')->changeTime();
            $this->returnMsg(1,'login success') && exit;
        }
        $this->returnMsg(0,'账号密码错误') && exit;
    }
}