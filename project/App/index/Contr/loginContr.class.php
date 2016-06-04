<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class loginContr extends Controller\HttpController{
    // 登入
    public function login(){
        $data = $this->post();
        $this->checkYzm($data);
        $re = obj('userModel')->userLogin($data['account'],$data['passwd']);
        if(isset($re['id'])){
            session_start();
            $_SESSION['id'] = $re['id'];
            $_SESSION['timeLogin'] = date('Y-m-d H:i:s', time());
            obj('userModel')->loginState($re['id'], $_SESSION['timeLogin'], $_SERVER['REMOTE_ADDR']);
            $_SESSION['account'] = $data['account'];
            $_SESSION['passwd'] = $data['passwd'];
            session_commit();
            $this->returnMsg(1, '登入成功!!!') && exit;
        }else $this->returnMsg(0, '账号密码有误!') && exit;
    }
    // 注册
    public function reg(){
        $data = $this->post();
        $this->checkYzm($data);
        $data['timeCreate'] = date('Y-m-d H:i:s', time());
        obj('userModel')->insertData($data) ? $this->returnMsg(1, 'reg成功!!!') : $this->returnMsg(0, 'reg 失败!!!') && exit;
    }
    // 验证码
    public function yzm(){
        session_start();
        obj('\Main\Expand\Image',true)->yzm() && exit;;
    }
    // 检测yzm
    private function checkYzm(&$data){
        session_start();
        if(isset($data['yzm']) && $data['yzm']==$_SESSION['yzm']){
            unset($data['yzm']);
            session_commit();
        }
        else $this->returnMsg(0, '验证码有误!') && exit;
    }
}