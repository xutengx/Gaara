<?php

declare(strict_types = 1);
namespace App\yh\s;
defined('IN_SYS') || exit('ACC Denied');

use Main\Core\Secure;

class Token {
    // 盐
    const key = 'yh';
    
    // token 有效时间
    const expired = 36000;
    
    /**
     * 由用户信息生成token
     * @param array $info
     * @return string
     */
    public static function encryptToken(array $info) : string{
        $info['token_start_time'] = time();
        $str = \json_encode($info);
        return Secure::encrypt($str, self::key);
    }
    
    /**
     * 解析token
     * @param string $token
     * @return array
     */
    public static function decryptToken(string $token) : array{
        $str = Secure::decrypt($token, self::key);
        $info = \json_decode($str, true);
        unset($info['token_start_time']);
        return $info;
    }
    
    /**
     * token是否过期
     * @param string $token
     * @return bool
     */
    public static function checkToken(string $token) : bool{
        $str = Secure::decrypt($token, self::key);
        $info = \json_decode($str, true);
        if((isset($info['token_start_time']) ? $info['token_start_time'] : 0)  + self::expired > time()){
            return true;
        }else{
            return false;
        }
    }
}
