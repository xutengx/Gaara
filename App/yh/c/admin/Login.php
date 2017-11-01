<?php

declare(strict_types = 1);
namespace App\yh\c\admin;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m\MainAdmin as GaaraAdmin ;
use Gaara\Core\Controller\HttpController;
use Gaara\Core\Request;
use App\yh\s\Token;

class Login extends HttpController {

    /**
     * 管理员登录
     * @param GaaraUser $user
     * @return type
     */
    public function index(GaaraAdmin $GaaraAdmin, Request $request) {
        $username = $this->post('username', 'string');
        $passwd = $this->post('passwd', 'passwd');
        // 查询用户信息
        if ($info = $GaaraAdmin->getUsername($username)) {
            if (password_verify($passwd, $info['passwd'])) {
                // 数据库更新用户登入状态, 缓存用户状态, 用于登入时校验
                $newInfo = $this->userLogin($info['id'], $GaaraAdmin, $request, $info);
                return $this->returnData($this->makeToken($newInfo));
            } else
                return $this->returnMsg(0, '密码错误');
        } else
            return $this->returnMsg(0, '此管理员账户不存在');
    }
    /**
     * 更换 token 令牌
     * @param string $token 原令牌
     */
    public function changeToken(GaaraAdmin $user, Request $request) {
        $token = $request->post('token','token');
        $tokenInfo = Token::decryptToken($token);
        if (is_array($tokenInfo) && isset($tokenInfo['username'])) {
            $userInfo = $user->getUsername($tokenInfo['username']);
            if (!empty($userInfo)) {
                if ($tokenInfo['passwd'] === $userInfo['passwd']) {
                    if ($tokenInfo['last_login_ip'] === $userInfo['last_login_ip']) {
                        if ($tokenInfo['last_login_at'] === $userInfo['last_login_at']) {
                            // 数据库更新用户登入状态, 缓存用户状态, 用于登入时校验
                            $newInfo = $this->userLogin($tokenInfo['id'], $user, $request, $tokenInfo);
                            return $this->returnData($this->makeToken($newInfo));
                        } else
                            return $this->returnMsg(0, '用户已在另一处登入');
                    } else
                        return $this->returnMsg(0, 'ip地址发生变动');
                } else
                    return $this->returnMsg(0, '密码已变更');
            } else
                return $this->returnMsg(0, '管理员不存在');
        } else
            return $this->returnMsg(0, '非法token');
    }

    /**
     * 更新登入状态(数据库 , 缓存)
     * @param int $id           用户主键
     * @param GaaraAdmin $user   userModel
     * @param Request $request  当前请求
     * @param array $tokenInfo  用户信息
     * @return array
     */
    private function userLogin(int $id, GaaraAdmin $user, Request $request, array $tokenInfo): array {
        $tokenInfo['last_login_ip'] = \ip2long($request->ip);
        $tokenInfo['last_login_at'] = \date('Y-m-d H:i:s');
        $user->login($id, $tokenInfo['last_login_ip'], $tokenInfo['last_login_at']);
        return $tokenInfo;
    }

    /**
     * 由用户信息生成 token
     * @param array $info
     * @return string
     */
    private function makeToken(array $info): string {
        return Token::encryptToken($info);
    }
}
