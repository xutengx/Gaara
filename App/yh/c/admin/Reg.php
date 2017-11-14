<?php

declare(strict_types = 1);
namespace App\yh\c\admin;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m\MainAdmin as GaaraAdmin;
use Gaara\Core\Request;
use Gaara\Core\Controller;
use PDOException;
use App\yh\s\Token;

class Reg extends Controller {

    /**
     * 新增管理员 ( 由管理员新增 )
     * @param GaaraAdmin  $GaaraAdmin      数据库操作对象
     */
    public function index( Request $request, GaaraAdmin $GaaraAdmin ) {
        $admin_id = $request->userinfo['id'];
        $username = $this->post('username', 'string');
        $passwd = $this->post('passwd','passwd');

        return $this->returnData(function() use ($GaaraAdmin, $username, $passwd, $admin_id){
            return $GaaraAdmin->createUser($username, $passwd, $admin_id);
        });
    }
    
    /**
     * 管理员 重新设置自己密码
     * @param GaaraAdmin $GaaraAdmin
     */
    public function setPasswd(Request $request, GaaraAdmin $GaaraAdmin) {
        $username = $request->userinfo['username'];
        $passwd = $this->put('passwd','passwd');
        $oldpasswd = $this->put('oldpasswd', 'passwd');

        // 查询用户信息
        if ($info = $GaaraAdmin->getUsername($username)) {
            if (password_verify($oldpasswd, $info['passwd'])) {
                
                // 写入数据库, 若失败则删除已保存的文件
                try{
                    $res = $GaaraAdmin->resetPasswd($username, $passwd);
                    $this->resetToken($request->userinfo['id']);
                    return $this->returnData($res);
                }catch(PDOException $pdo){
                    return $this->returnMsg(0, $pdo->getMessage());
                }
            } else
                return $this->returnMsg(0, '原密码错误');
        } else
            return $this->returnMsg(0, '此管理员账户不存在');
    }
    
    private function resetToken(int $id):bool{
        return Token::removeToken($id);
    }

}
