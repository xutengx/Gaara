<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/22 0022
 * Time: 11:05
 */
namespace Main\Core;

class Secure {

    // 加解密关键字
    private $key = 'key';

    //延伸的md5方法
    public function md5($string) {
        return md5($string . md5($string));
    }

    // 返回带有header的ajax方法,依赖同用版本的jquery
    // 应用于Controller->display();
    // return string
    public function csrfAjax($classname) {
        $time = $_SERVER['REQUEST_TIME'];
        $csrftoken = $this->encrypt($classname . '|' . $time);
        return '(function($){var _ajax=$.ajax;$.ajax=function(opt){ var fn = {beforeSend: function(request) {}};if(opt.beforeSend) fn.beforeSend=opt.beforeSend; var _opt = $.extend(opt,{beforeSend: function(request) {request.setRequestHeader("SCRFTOKEN", "' . $csrftoken . '");fn.beforeSend(request); }});_ajax(_opt);};})(jQuery);';
    }

    // 核对http头部的csrftaoken
    // 应用于Controller->post();
    // return bool
    public function checkCsrftoken($classname, $overTime) {
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

    //特殊字符过滤
    public function symbol($string, $is_strict = false) {
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
        $key = $key ? md5($key) : md5($this->key);
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
        $key = $key ? md5($key) : md5($this->key);
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

    //xss检测（check）、过滤（filter）
    public function xssCheck($string, $mode = 'check') {
        $regexp_list = include_once(ROOT . 'Main/Conf/Secure/Xss.conf.php');
        if ($mode === 'check') {
            $risk = 0;
            foreach ($regexp_list as $regexp) {
                if (preg_match($regexp, $string)) {
                    $risk++;
                }
            }
            return $risk;
        }
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

    //判断是否异步请求
    public function is_ajax() {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'))
            return true;
        return false;
    }
}
