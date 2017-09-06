<?php

declare(strict_types = 1);
namespace App\yh\m;
defined('IN_SYS') || exit('ACC Denied');

class MainAdmin extends \Main\Core\Model {

    // 密码加密算法
    const encryption = PASSWORD_BCRYPT;

    /**
     * 加密保存, 并发下处理
     * @param string $username
     * @param string $passwd
     * @return boolean
     */
    public function createUser(string $username, string $passwd) {
        $hashPasswd = password_hash($passwd, self::encryption);
        try{
            return $this->data([
                'username' => ':username',
                'passwd' => ':passwd'
            ])
            ->insert([
                ':username' => $username,
                ':passwd' => $hashPasswd
            ]);
        }catch (\Exception $e){
            return false;
        }
    }
    /**
     * 加密保存, 并发下处理
     * @param string $username
     * @param string $passwd
     * @return boolean
     */
    public function resetPasswd(string $username, string $passwd) {
        $hashPasswd = password_hash($passwd, self::encryption);
        try{
            return $this->where([
                'username' => ':username'
            ])->data([
                'passwd' => ':passwd'
            ])
            ->update([
                ':username' => $username,
                ':passwd' => $hashPasswd
            ]);
        }catch (\Exception $e){
            return false;
        }
    }
    
    
    
    /**
     * 查询用户名
     * @param string $username
     * @return array
     */
    public function getUsername(string $username) : array{
        return $this->where('username', $username)->getRow();
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
