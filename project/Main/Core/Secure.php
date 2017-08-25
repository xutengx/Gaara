<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/22 0022
 * Time: 11:05
 */
namespace Main\Core;
defined('IN_SYS') || exit('ACC Denied');

class Secure {

    // 盐
    const key = 'key';

    /**
     * md5
     * @param string $string
     * @return string
     */
    public function md5(string $string): string {
        return md5($string . md5($string));
    }

    /**
     * 返回带有header的ajax方法,依赖jquery, 应用于Controller->display();
     * @param string $classname
     * @return string
     */
    public function csrfAjax(string $classname): string {
        $time = $_SERVER['REQUEST_TIME'];
        $csrftoken = $this->encrypt($classname . '|' . $time);
        return '(function($){var _ajax=$.ajax;$.ajax=function(opt){ var fn = {beforeSend: function(request) {}};if(opt.beforeSend) fn.beforeSend=opt.beforeSend; var _opt = $.extend(opt,{beforeSend: function(request) {request.setRequestHeader("SCRFTOKEN", "' . $csrftoken . '");fn.beforeSend(request); }});_ajax(_opt);};})(jQuery);';
    }

    /**
     * 核对http头部的csrftaoken, 应用于Controller->post();等
     * @param string $classname
     * @param int $overTime
     * @return bool
     */
    public function checkCsrftoken(string $classname, int $overTime): bool {
        if ($overTime === 0)
            return true;
        if (isset($_SERVER['HTTP_SCRFTOKEN']) && $csrftoken = $_SERVER['HTTP_SCRFTOKEN']) {
            $str = $this->decrypt($csrftoken);
            $arr = explode('|', $str);
            if ($classname != $arr[0])
                return false;
            if (($overTime != 0 && (($arr[1] + $overTime) > $_SERVER['REQUEST_TIME'])) || $overTime === 0)
                return true;
        }
        return false;
    }

    /**
     * 过滤特殊(删除)字符
     * @param string $string
     * @param bool $is_strict   严格模式下, 将过滤更多
     * @return string
     */
    public function symbol(string $string, bool $is_strict = false): string {
        $risk = '~^<>`\'"\\';
        $is_strict and $risk .= '@!#$%&?+-*/={}[]()|,.:;';
        $risk = str_split($risk, 1);
        return str_replace($risk, '', $string);
    }

    /**
     * 加密
     * @param string $string
     * @param string $key
     * @return string
     */
    public function encrypt(string $string, string $key = ''): string {
        $key = $key ? md5($key) : md5(self::key);
        $j = 0;
        $buffer = $data = '';
        $length = strlen($string);
        for ($i = 0; $i < $length; $i++) {
            if ($j == 32) {
                $j = 0;
            }
            $buffer .= $key[$j];
            $j++;
        }
        for ($i = 0; $i < $length; $i++) {
            $data .= $string[$i] ^ $buffer[$i];
        }
        return $this->base64_encode($data);
    }

    /**
     * 解密
     * @param string $string
     * @param string $key
     * @return string
     */
    public function decrypt(string $string, string $key = ''): string {
        $key = $key ? md5($key) : md5(self::key);
        $string = $this->base64_decode($string);
        $j = 0;
        $buffer = $data = '';
        $length = strlen($string);
        for ($i = 0; $i < $length; $i++) {
            if ($j == 32) {
                $j = 0;
            }
            $buffer .= substr($key, $j, 1);
            $j++;
        }
        for ($i = 0; $i < $length; $i++) {
            $data .= $string[$i] ^ $buffer[$i];
        }
        return $data;
    }

    /**
     * 过滤script
     * @param string $string
     * @return string
     */
    public function xssCheck(string $string): string {
        return obj('HTMLPurifier')->purify($string);
    }

    /**
     * URL安全的字符串编码
     * @param string $string
     * @return string
     */
    public function base64_encode(string $string): string {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    /**
     * URL安全的字符串编码的解码
     * @param string $string
     * @return string
     */
    public function base64_decode(string $string): string {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}
