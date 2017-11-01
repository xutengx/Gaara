<?php

declare(strict_types = 1);
namespace Gaara\Core;

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
     * 将cookie中的X-CSRF-TOKEN加入ajax请求头
     * @return string
     */
    public function csrfAjax(): string {
        return <<<EOF
$.ajaxSetup({beforeSend:function(a){var b=window.document.cookie.match(/(?:^|\s|;)X-XSRF-TOKEN\s*=\s*([^;]+)(?:;|$)/);a.setRequestHeader("X-XSRF-TOKEN",b&&b[1])},error:function(a,b,c){a=JSON.parse(a.responseText);void 0!==a.msg?alert(a.msg):void 0!==a.error.message&&alert(a.error.message)}});
EOF;
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
            if ($j === 32) {
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
            if ($j === 32) {
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
