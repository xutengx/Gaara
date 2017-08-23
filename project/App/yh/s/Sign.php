<?php

declare(strict_types = 1);
namespace App\yh\s;
defined('IN_SYS') || exit('ACC Denied');

/**
 * 将参数数组按键从小到大排序(注意,int均转换为string)
 * 然后依次拼接上当前时间戳(timestamp),用户令牌(token)
 * 再转化为json,再md5后加盐再md5;
 */
class Sign {

    // 盐
    const key = 'yh';
    // 请求允许的误差时间
    const expired = 600;

    /**
     * 检测sign
     * @param array $param      请求中的参数 (不包含 token,timestamp,sign)
     * @param string $token     用户token
     * @param int $timestamp    时间戳
     * @param string $sign      待核对的sign
     * @return bool
     */
    public static function checkSign(array $param, string $token, int $timestamp, string $sign): bool {
        if ($timestamp + self::expired < time())
            return false;
        unset($param['token']);
        unset($param['timestamp']);
        unset($param['sign']);
        ksort($param);
        $param['timestamp'] = $timestamp;
        $param['token'] = $token;
        $str = \json_encode($param, JSON_UNESCAPED_UNICODE);
//        $str = \urldecode($str);
        var_dump('{"age":"22","email":"123123@qq.com","name":"xutengh回回千位","timestamp":1503468631,"token":"QhpYXENcCQRUTRQDVFdcWxtfGwhSUwIBBlUFAFdwEkQXW15VQ0oaRAUSRRFdFA8VHVdAHVRUF1VnEQ5pEgggdmB3ZX5TE19/NTJlMEwDWQFzVFRSMlJ3BwwSW0oXUS15SAFIZE4PfA0IBnsFWBQZFUoRWE0QFxELBEoUVQRDF2pVV1ZRDzlRREZbBVQKBAcDD1ALAElGX1BGEmlVClcKW2ZZRRpbRAoEVVYbVgEbBwQZVAsDVFMJAwJEGhsGQgZUTV1VZwASGg5GUwZXDhsFDxRXChlUVQkCAlwGDEccQUBJXFBMBAJnVRBDDEQLBgQAFFUBFFdXEwAEXAMBXwBVFxUaRVcKA1ZrFxVXFE1pQV5UABsDVFEDAgFQBwFRBx4="}');
        var_dump($str);
        var_dump(trim($str));
        var_dump(md5($str));
        var_dump(md5($str) . self::key);
        var_dump(md5(md5($str) . self::key));
        return ($sign === md5(md5($str) . self::key));
    }
}
