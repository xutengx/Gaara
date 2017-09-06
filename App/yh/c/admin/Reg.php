<?php

declare(strict_types = 1);
namespace App\yh\c\admin;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m\MainAdmin;
use Main\Core\Request;
use Main\Core\Controller\HttpController;

class Reg extends HttpController {

    /**
     * 新增管理员 ( 由管理员新增 )
     * @param MainAdmin  $MainAdmin      数据库操作对象
     */
    public function index( MainAdmin $MainAdmin ) {
        $username = $this->post('username', 'string');
        $passwd = $this->post('passwd','passwd');

        return $this->returnData($MainAdmin->createUser($username, $passwd));
    }
    
    /**
     * 管理员 重新设置密码
     * @param MainAdmin $MainAdmin
     */
    public function setPasswd(Request $request, MainAdmin $MainAdmin) {
        $username = $request->userinfo['username'];
        $passwd = $this->post('passwd','passwd');
        $oldpasswd = $this->post('oldpasswd', 'passwd');

        // 查询用户信息
        if ($info = $MainAdmin->getUsername($username)) {
            if (password_verify($oldpasswd, $info['passwd'])) {
                return $this->returnData($MainAdmin->resetPasswd($username, $passwd));
            } else
                return $this->returnMsg(0, '原密码错误');
        } else
            return $this->returnMsg(0, '此管理员账户不存在');
    }

}
