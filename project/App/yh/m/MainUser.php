<?php

namespace App\yh\m;

defined('IN_SYS') || exit('ACC Denied');

class MainUser extends \Main\Core\Model {
    // 密码加密算法
    const encryption = PASSWORD_BCRYPT;
    
    /**
     * 加密保存
     * @param string $email     登录邮箱
     * @param string $passwd    登录密码
     * @return type
     */
    public function createUser(string $email, string $passwd){
        $hashPasswd = password_hash($passwd, self::encryption);
        return $this->data([
            'email' => ':email',
            'passwd' => ':passwd'
        ])
        ->insert([
            ':email' => $email,
            ':passwd' => $hashPasswd
        ]);
    }
    
}
