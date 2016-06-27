<?php
namespace App\index\Wcaht;
defined('IN_SYS')||exit('ACC Denied');
class tokenWchat{
    function wx_get_token() {
        $token = S('access_token');
        if (!$token) {
            $res = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='            .'你的AppID'.'&secret='            .'你的AppSecret');
            $res = json_decode($res, true);

            $token = $res['access_token'];
            S('access_token', $token, 3600);

        }

        return $token;

    }
}