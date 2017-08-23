<?php

declare(strict_types = 1);
namespace App\yh\s;
defined('IN_SYS') || exit('ACC Denied');

use Secure;
use Cache;

/**
 * info由用户的main_user表信息生成的一维数组, 并加入token_start_time字段,值为当前时间戳
 * json_encode之后使用加密(解密反之)
 */
class Token {

    // 盐
    const key = 'yh';
    // token 有效时间
    const expired = 10800;
    // 用户存储状态的前缀
    const prefix = 'yhuid';

    /**
     * 由用户信息生成token
     * @param array $info
     * @return string
     */
    public static function encryptToken(array $info): string {
        $info['token_start_time'] = time();
        $str = \json_encode($info);
        $token = Secure::encrypt($str, self::key);
        Cache::set(self::makeId($info['id']), $token, self::expired);
        return $token;
    }

    /**
     * 解析token
     * @param string $token
     * @return array
     */
    public static function decryptToken(string $token): array {
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
    public static function checkToken(string $token): bool {
        $str = Secure::decrypt($token, self::key);
        $info = \json_decode($str, true);
        $tokenInCache = Cache::get(self::makeId($info['id']));
        
        if (($tokenInCache === $token) && (isset($info['token_start_time']) ? $info['token_start_time'] : 0) + self::expired > time()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 生成用与用户登入状态保持的key
     * @param int $id
     * @return string
     */
    private static function makeId(int $id): string {
        return self::prefix . $id;
    }
}
