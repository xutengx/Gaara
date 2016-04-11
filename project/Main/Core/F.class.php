<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/21 0021
 * Time: 17:14
 */
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
// 原filter类, 数据来源过滤
class F{
    public static $post;
    public static $get;
    public static $put;
    public static $delete;
    public static $cookie;
//    private static $ins = null;
    private static $filterArr = array(
        'email' => '/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/',
        'email2' => '/^[0-9a-z][_.0-9a-z-]{0,31}@([0-9a-z][0-9a-z-]{0,30}[0-9a-z]\.){1,4}[a-z]{2,4}$/i',
        'url'   => '/^[a-zA-z]+://(\w+(-\w+)*)(\.(\w+(-\w+)*))*(\?\S*)?$/',
        'int'   => '/^-?\d+$/',
        // 帐号(字母开头，允许5-16字节，允许字母数字下划线)
        'account' => '/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/',
        // 邮编
        'mail'  => '/^[1-9]\d{5}$/',
        // 中国手机号码：(86)*0*13\d{9}
        'phone' => '/^1[3|4|5|7|8][0-9]\d{8}$/',
        // 中国手机号码：(86)*0*13\d{9}
        'tel' => '/^1[3|4|5|7|8][0-9]\d{8}$/',
        // 大小写字母,数字,下划线
        'string' => '/^\w+$/',
        // 大小写字母,数字,下划线
        'sign' => '/^{0,200}$/',
        // 2-8位
        'name'  => '/^[_\w\d\x{4e00}-\x{9fa5}]{2,8}$/iu'

    );

    final public function __construct($par){
        $this->filterMake($par);
    }
    final private function __clone(){}
    private function filterMake($par){
        self::$get = self::_addslashes(self::_htmlspecialchars($par));
        self::$post = self::_addslashes(self::_htmlspecialchars($_POST));
        self::$cookie = self::_addslashes(self::_htmlspecialchars($_COOKIE));
        self::getPUTorDELETE();
    }
    public static function get($key, $filter = false){
        if(isset(self::$get[$key]))  return $filter ? self::filterMatch(self::$get[$key], $filter) : self::$get[$key];
        else return null;
    }
    public static function post($key, $filter = false){
        if(isset(self::$post[$key])) return $filter ? self::filterMatch(self::$post[$key], $filter) : self::$post[$key];
        else return null;
    }
    public static function put($key, $filter = false){
        if(isset(self::$put[$key])) return $filter ? self::filterMatch(self::$put[$key], $filter) : self::$put[$key];
        else return null;
    }
    public static function delete($key, $filter = false){
        if(isset(self::$delete[$key])) return $filter ? self::filterMatch(self::$delete[$key], $filter) : self::$delete[$key];
        else return null;
    }
    public static function cookie($key, $filter = false){
        if(isset(self::$cookie[$key])) return $filter ? self::filterMatch(self::$cookie[$key], $filter) : self::$cookie[$key];
        else return null;
    }
    public static function session($key, $filter = false){
        if(isset($_SESSION[$key])) return $filter ? self::filterMatch($_SESSION[$key], $filter) : $_SESSION[$key];
        else return null;
    }
//    public static function getins($par=null){
//        if(self::$ins instanceof self || self::$ins = new self($par)) return self::$ins;
//    }
    public static function set_cookie($k, $v){
        self::$cookie[$k] = $v;
    }
    private static function getPUTorDELETE(){
        if ('PUT' == $_SERVER['REQUEST_METHOD']) {
            parse_str(file_get_contents('php://input'), self::$put);
        }else if('DELETE' == $_SERVER['REQUEST_METHOD']) {
            parse_str(file_get_contents('php://input'), self::$delete);
        }
    }

    /**
     * 正则匹配
     * @param string $str    检验对象
     * @param string $filter 匹配规则
     * @return mixed or false
     */
    private static function filterMatch($str, $filter){
        if(strtolower($filter === 'xss') ){
            return Secure::xss($str, 'filter'); // 返回过滤后的$str
        }else {
            $filter = array_key_exists($filter, self::$filterArr) ? self::$filterArr[$filter] : $filter;
            return preg_match($filter, $str) ? $str : false ;
        }
    }
    private static function _addslashes($arr){
        $q = array();
        foreach($arr as $k=>$v){
            if(is_string($v)){
                $q[addslashes($k)] = addslashes($v);
            }else if (is_array($v)){
                $q[addslashes($k)] = self::_addslashes($v);
            }
        }
        return $q;
    }
    private static function _htmlspecialchars($arr){
        $q = array();
        foreach($arr as $k=>$v){
            if(is_string($v)){
                $q[($k)] = htmlspecialchars($v);
            }else if (is_array($v)){
                $q[($k)] = self::_htmlspecialchars($v);
            }
        }
        return $q;
    }

    /**
     * @param $string 原字符串
     * @param $length 目标长度
     * @param bool|false $havedot 多余展示符,false则没有, 如 ...
     * @param string $charset
     * @return mixed|string
     */
    public static function cutstr($string, $length, $havedot = false, $charset = 'utf8'){
        if (strtolower($charset) == 'gbk') $charset = 'gbk';
        else $charset = 'utf8';
        if (strlen($string) <= $length)  return $string;
        if (function_exists('mb_strcut'))  $string = mb_substr($string, 0, $length, $charset);
        else {
            $pre = '{%';  $end = '%}';
            $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);
            $strcut = '';
            $strlen = strlen($string);
            $n = $tn = $noc = 0;
            if ($charset == 'utf8') {
                while ($n < $strlen) {
                    $t = ord($string[$n]);
                    if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                        $tn = 1;
                        $n++;
                        $noc++;
                    } elseif (194 <= $t && $t <= 223) {
                        $tn = 2;
                        $n += 2;
                        $noc++;
                    } elseif (224 <= $t && $t <= 239) {
                        $tn = 3;
                        $n += 3;
                        $noc++;
                    } elseif (240 <= $t && $t <= 247) {
                        $tn = 4;
                        $n += 4;
                        $noc++;
                    } elseif (248 <= $t && $t <= 251) {
                        $tn = 5;
                        $n += 5;
                        $noc++;
                    } elseif ($t == 252 || $t == 253) {
                        $tn = 6;
                        $n += 6;
                        $noc++;
                    } else {
                        $n++;
                    }
                    if ($noc >= $length) {
                        break;
                    }
                }
                if ($noc > $length) {
                    $n -= $tn;
                }
                $strcut = substr($string, 0, $n);
            } else {
                while ($n < $strlen) {
                    $t = ord($string[$n]);
                    if ($t > 127) {
                        $tn = 2;
                        $n += 2;
                        $noc++;
                    } else {
                        $tn = 1;
                        $n++;
                        $noc++;
                    }
                    if ($noc >= $length) {
                        break;
                    }
                }
                if ($noc > $length) {
                    $n -= $tn;
                }
                $strcut = substr($string, 0, $n);
            }
            $string = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
        }
        if ($havedot) $string = $string . $havedot;
        return $string;
    }
    // 外部获取 预定义验证规则
    public static function getFilterArr(){
        return self::$filterArr;
    }
}