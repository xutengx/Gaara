<?php

declare(strict_types = 1);
namespace App\yh\m;
defined('IN_SYS') || exit('ACC Denied');

class MainUser extends \Main\Core\Model {

    // 密码加密算法
    const encryption = PASSWORD_BCRYPT;

    /**
     * 加密保存, 并发下处理
     * @param string $email
     * @param string $passwd
     * @return boolean
     */
    public function createUser(string $email, string $passwd) {
        $hashPasswd = password_hash($passwd, self::encryption);
        try{
            return $this->data([
                'email' => ':email',
                'passwd' => ':passwd'
            ])
            ->insert([
                ':email' => $email,
                ':passwd' => $hashPasswd
            ]);
        }catch (\Exception $e){
            return false;
        }
    }
    /**
     * 查询用户名
     * @param string $email
     * @return array
     */
    public function getEmail(string $email) : array{
        return $this->where('email', $email)->getRow();
    }

    
    /**
     * 登入, 并更新用户登入状态
     * @param int $id
     * @param int $ip
     * @param string $time  格式化后的时间
     * @return type
     */
    public function login(int $id, int $ip, string $time){
        return $this->data([
            'last_login_ip' => $ip,
            'last_login_at' => $time
        ])->where('id', $id)
        ->update();
    }
}
