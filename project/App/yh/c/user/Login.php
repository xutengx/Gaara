<?php

declare(strict_types = 1);
namespace App\yh\c\user;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m\MainUser;
use Main\Core\Controller\HttpController;
use Main\Core\Request;
use App\yh\s\Token;

class Login extends HttpController {

    /**
     * 用户登录
     * @param MainUser $user
     * @return type
     */
    public function index(MainUser $user, Request $request) {
        $email = $this->post('email', 'email');
        $passwd = $this->post('passwd', 'passwd');

        if ($info = $user->getEmail($email)) {
            if (password_verify($passwd, $info['passwd'])) {
                if ($info['status'] === 1) {
                    // 数据库更新用户登入状态, 缓存用户状态, 用于登入时校验
                    $newInfo = $this->userLogin($info['id'], $user, $request);
                    
                    return $this->returnData($this->makeToken($newInfo));
                } else
                    return $this->returnMsg(0, '用户已被禁用');
            } else
                return $this->returnMsg(0, '密码错误');
        } else
            return $this->returnMsg(0, '此邮箱没有注册');
    }
    /**
     * 更换 token 令牌
     * @param string $token 原令牌
     */
    public function changeToken(MainUser $user, Request $request) {
        $token = $request->post('token', 'string');
        $tokenInfo = Token::decryptToken($token);
        if (is_array($tokenInfo) && isset($tokenInfo['email'])) {
            $userInfo = $user->getEmail($tokenInfo['email']);
            if (!empty($userInfo)) {
                if ($tokenInfo['passwd'] === $userInfo['passwd']) {
                    if ($tokenInfo['status'] === 1) {
                        if ($tokenInfo['last_login_ip'] === $userInfo['last_login_ip']) {
                            if ($tokenInfo['last_login_at'] === $userInfo['last_login_at']) {
                                // 数据库更新用户登入状态, 缓存用户状态, 用于登入时校验
                                $newInfo = $this->userLogin($tokenInfo['id'], $user, $request);
                                return $this->returnData($this->makeToken($newInfo));
                            } else
                                return $this->returnMsg(0, '用户已在另一处登入');
                        } else
                            return $this->returnMsg(0, 'ip地址发生变动');
                    } else
                        return $this->returnMsg(0, '用户已禁用');
                } else
                    return $this->returnMsg(0, '密码已变更');
            } else
                return $this->returnMsg(0, '用户不存在');
        } else
            return $this->returnMsg(0, '非法token');
    }

    /**
     * 更新登入状态(数据库 , 缓存)
     * @param int $id           用户主键
     * @param MainUser $user    userModel
     * @param Request $request  当前请求
     * @return array
     */
    private function userLogin(int $id, MainUser $user, Request $request): array {
        $info['last_login_ip'] = \ip2long($request->ip);
        $info['last_login_at'] = \date('Y-m-d H:i:s');
        $user->login($id, $info['last_login_ip'], $info['last_login_at']);
        return $info;
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
