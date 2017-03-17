<?php

namespace App\admin\Contr;
use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class loginContr extends Controller\HttpController {
    
    public function indexDo() {
        $this->display();
    }
    
    public function login(){
        $username = $this->post('username');
        $passwd = $this->post('passwd');
        $remember = $this->post('remember');
        
        $re = obj('adminModel')->login($username, md5($passwd));
        if(!empty($re)){
            list($re['last_login_time'], $re['last_login_ip']) = obj('adminModel')->change_last_login_time_and_ip($re['id']);  
            $_SESSION['admin'] = $re;
            if($remember === 'on,on')
                $_SESSION['remember'] = 1;
            $this->returnMsg(1,'success') && exit;
        }
        $this->returnMsg(0) && exit;
    }
    
    public function logout(){
        $_SESSION = [];
        $this->returnMsg(1,'success') && exit;
    }
}