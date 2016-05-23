<?php
namespace Main\Core\Controller\Module;
defined('IN_SYS')||exit('ACC Denied');
trait CookieModule{
    // cookie即时生效
    protected function setcookie($var, $value = '', $time = 0, $path = '', $domain = '', $s = false){
        $_COOKIE[$var] = $value;
        obj('F')->set_cookie($var , $value);
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                setcookie($var . '[' . $k . ']', $v, $time, $path, $domain, $s);
            }
        } else setcookie($var, $value, $time, $path, $domain, $s);
    }

}