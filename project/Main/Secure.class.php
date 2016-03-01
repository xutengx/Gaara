<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/22 0022
 * Time: 11:05
 */
namespace Main;
class Secure{
    //延伸的md5方法
    public static function md5($string){
        return md5($string.md5($string));
    }

    // 返回带有header的ajax方法,依赖同用版本的jquery
    // 应用于Controller->display();
    // return string
    public static function newAjax($csrftoken){
        $csrftoken = self::encrypt($csrftoken);
        return '(function($){var _ajax=$.ajax;$.ajax=function(opt){ var fn = {beforeSend: function(request) {}};if(opt.beforeSend) fn.beforeSend=opt.beforeSend; var _opt = $.extend(opt,{beforeSend: function(request) {request.setRequestHeader("SCRFTOKEN", "'.$csrftoken.'");fn.beforeSend(request); }});_ajax(_opt);};})(jQuery);';
    }
    // 核对http头部的csrftaoken
    // 应用于Controller->post();
    // return bool
    public static function checkCsrftoken($csrftoken){
        $csrftoken = self::encrypt($csrftoken);
        return (isset($_SERVER['HTTP_SCRFTOKEN']) && $_SERVER['HTTP_SCRFTOKEN'] == $csrftoken) ? true : false;
    }
    //特殊字符过滤
    public static function symbol($string,$is_strict=false){
        $risk='~^<>`\'"\\';
        $is_strict and $risk.='@!#$%&?+-*/={}[]()|,.:;';
        $risk=str_split($risk,1);
        return str_replace($risk,'',$string);
    }
    //加密
    public static function encrypt($string,$key='key'){
        $j=0;
        $key=md5($key);
        $buffer=$data='';
        $length=strlen($string);
        for ($i=0; $i<$length; $i++) {
            if ($j == 32) {
                $j=0;
            }
            $buffer .= $key[$j];
            $j++;
        }
        for ($i=0; $i<$length; $i++) {
            $data .= $string[$i] ^ $buffer[$i];
        }
        return base64_encode($data);
    }

    //解密
    public static function decrypt($string,$key='key'){
        $string=base64_decode($string);
        $j=0;
        $key=md5($key);
        $buffer=$data='';
        $length=strlen($string);
        for ($i=0; $i<$length; $i++) {
            if ($j == 32) {
                $j=0;
            }
            $buffer .= substr($key,$j,1);
            $j++;
        }
        for ($i=0; $i<$length; $i++) {
            $data .= $string[$i] ^ $buffer[$i];
        }
        return $data;
    }

    //xss检测（check）、过滤（filter）
    public static function xss($string,$mode='check'){
        $regexp_list = include(ROOT.'Main/Conf/Secure/Xss.conf.php');
        if ($mode === 'check') {
            $risk=0;
            foreach ($regexp_list as $regexp) {
                if (preg_match($regexp,$string)) {
                    $risk++;
                }
            }
            return $risk;
        }
        return preg_replace($regexp_list,'',$string);
    }

    //判断是否异步请求
    public static function is_ajax(){
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) return true;
        return false;
    }
}