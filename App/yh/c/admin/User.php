<?php

declare(strict_types = 1);
namespace App\yh\c\admin;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m\MainUser;
use Main\Core\Request;
use Main\Core\Controller\HttpController;
use PDOException;
use App\yh\s\Token;

/**
 * 管理员, 对用户的权限管理 (登入的启用/禁用, 支付的启用/禁用)
 */
class User extends HttpController {
    
    /**
     * 查询用户信息
     * @param Request $request
     * @param UserMerchant $merchant
     * @return type
     */
    public function select(MainUser $MainUser) {
        return $this->returnData(function() use ($MainUser){
            return $MainUser->getAll();
        });
    }

    /**
     * 新增用户信息
     * @param Request $request
     * @param UserMerchant $merchant
     */
    public function create() {
        return $this->returnMsg(0, '管理员不可以新增用户');
    }

    /**
     * 更新用户信息
     * @param Request $request
     * @param MainUser $MainUser
     */
    public function update(Request $request, MainUser $MainUser) {
        $user_id = (int)$this->input('id');
        $userInfo = $request->input;
        // 原数据
        $userOldInfo = $MainUser->getId( $user_id );
        if(empty($userOldInfo))
            return $this->returnMsg(0, '要修改的用户不存在');
    
        $MainUser->orm = $userInfo;
       
        // 写入数据库, 若失败则删除已保存的文件
        try{
            $res = $MainUser->save($userOldInfo['id']);
            Token::removeToken($user_id);
            return $this->returnData($res);
        }catch(PDOException $pdo){
            return $this->returnMsg(0, $pdo->getMessage());
        }
    }

    /**
     * 删除用户信息
     * @return type
     */
    public function destroy() {
        return $this->returnMsg(0, '管理员不可以删除用户');
    }
}
