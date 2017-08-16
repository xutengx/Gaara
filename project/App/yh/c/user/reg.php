<?php

declare(strict_types = 1);
namespace App\yh\c\user;
defined('IN_SYS') || exit('ACC Denied');

use Request;
use App\yh\m\mainUser;
use Main\Core\Controller\HttpController;

class reg extends HttpController {

    /**
     * 用户邮箱注册注册
     * @param \Request $requset
     */
    public function index() {
        $email = $this->post('email','email');
        if(!$this->checkEmail($email))
            return $this->returnMsg(0, '邮箱已被注册!!');
        
    }

    /**
     * 检测邮箱是否可用
     * @param Request $requset
     * @return json
     */
    public function email(){
        $email = $this->get('email','email');
        return $this->returnData($this->checkEmail($email));
    }
    
    /**
     * 检测邮箱是否已经被注册
     */
    private function checkEmail(string $email): bool {
        $user = mainUser::where('email',$email)->getRow();
        if($user)
            return false;
        return true;
    }
}
