<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/21 0021
 * Time: 17:14
 */
namespace Main;
defined('IN_SYS')||exit('ACC Denied');
// 原filter类, 数据来源过滤
class F{
    public static $post;
    public static $get;
    public static $cookie;
    private static $ins = null;
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
        // 大小写字母,数字,下划线
        'string' => '/^\w+$/',
        // 大小写字母,数字,下划线
        'sign' => '/^{0,200}$/',
        // 2-8位
        'name'  => '/^[_\w\d\x{4e00}-\x{9fa5}]{2,8}$/iu'

    );

    final private function __construct($par){
        $this->filterMake($par);
    }
    final private function __clone(){}
    private function filterMake($par){
        self::$get = self::_addslashes(self::_htmlspecialchars($par));
        self::$post = self::_addslashes(self::_htmlspecialchars($_POST));
        self::$cookie = self::_addslashes(self::_htmlspecialchars($_COOKIE));
    }
    public static function get($key, $filter = false){
        if(isset(self::$get[$key]))  return $filter ? self::filterMatch(self::$get[$key], $filter) : self::$get[$key];
        else return null;
    }
    public static function post($key, $filter = false){
        if(isset(self::$post[$key])) return $filter ? self::filterMatch(self::$post[$key], $filter) : self::$post[$key];
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
    public static function getins($par){
        if(self::$ins instanceof self || self::$ins = new self($par)) return self::$ins;
    }
    public static function set_cookie($k, $v){
        self::$cookie[$k] = $v;
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
}