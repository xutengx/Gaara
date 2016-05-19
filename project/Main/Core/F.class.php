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
    private $post = [];
    private $get = [];
    private $put = [];
    private $delete = [];
    private $cookie = [];
    private $filterArr = array(
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
        $this->getContentType($par);
    }
    private function getContentType($par){
        $this->get = $this->_addslashes($this->_htmlspecialchars($par));
        $this->cookie = $this->_addslashes($this->_htmlspecialchars($_COOKIE));
        if( ($argc = strtolower($_SERVER['REQUEST_METHOD'])) != 'get'){
            $this->{$argc} = file_get_contents('php://input');
            switch($_SERVER['CONTENT_TYPE']){
                case 'application/x-www-form-urlencoded':
                    parse_str($this->{$argc}, $this->{$argc});
                    $this->{$argc} = $this->_addslashes($this->_htmlspecialchars($this->{$argc}));
                    break;
                case 'application/json':
                    $this->{$argc} = json_decode($this->{$argc}, true);
                    break;
                case 'application/xml':
                    $this->{$argc} = obj('tool')->xml_decode($this->{$argc});
                    break;
                default:
                    break;
            }
        }
    }
    public function get($key, $filter = false){
        if(isset($this->get[$key]))  return $filter ? $this->filterMatch($this->get[$key], $filter) : $this->get[$key];
        else return null;
    }
    public function post($key, $filter = false){
        if(isset($this->post[$key])) return $filter ? $this->filterMatch($this->post[$key], $filter) : $this->post[$key];
        else return null;
    }
    public function put($key, $filter = false){
        if(isset($this->put[$key])) return $filter ? $this->filterMatch($this->put[$key], $filter) : $this->put[$key];
        else return null;
    }
    public function delete($key, $filter = false){
        if(isset($this->delete[$key])) return $filter ? $this->filterMatch($this->delete[$key], $filter) : $this->delete[$key];
        else return null;
    }
    public function cookie($key, $filter = false){
        if(isset($this->cookie[$key])) return $filter ? $this->filterMatch($this->cookie[$key], $filter) : $this->cookie[$key];
        else return null;
    }
    public function session($key, $filter = false){
        if(isset($_SESSION[$key])) return $filter ? $this->filterMatch($_SESSION[$key], $filter) : $_SESSION[$key];
        else return null;
    }
    public function set_cookie($k, $v){
        $this->cookie[$k] = $v;
    }
    /**
     * 正则匹配
     * @param string $str    检验对象
     * @param string $filter 匹配规则
     * @return mixed or false
     */
    private function filterMatch($str, $filter){
        if(strtolower($filter === 'xssf')){
            return obj('secure')->xssCheck($str, 'filter');
        }
        else if(strtolower($filter === 'xss') ){
            return obj('secure')->xssCheck($str, 'check'); // 返回过滤后的$str
        }else {
            $filter = array_key_exists($filter, $this->filterArr) ? $this->filterArr[$filter] : $filter;
            return preg_match($filter, $str) ? $str : false ;
        }
    }
    private function _addslashes($arr){
        $q = array();
        foreach($arr as $k=>$v){
            if(is_string($v)){
                $q[addslashes($k)] = addslashes($v);
            }else if (is_array($v)){
                $q[addslashes($k)] = $this->_addslashes($v);
            }
        }
        return $q;
    }
    private function _htmlspecialchars($arr){
        $q = array();
        foreach($arr as $k=>$v){
            if(is_string($v)){
                $q[($k)] = htmlspecialchars($v);
            }else if (is_array($v)){
                $q[($k)] = $this->_htmlspecialchars($v);
            }
        }
        return $q;
    }
    // 外部获取 预定义验证规则
    public function getFilterArr(){
        return $this->filterArr;
    }

    /**
     * 获取原始数据数组
     * @param $property_name
     *
     * @return mixed
     * @throws Exception
     */
    public function __get($property_name){
        if(in_array(strtolower($property_name),array('post','get','put','delete','cookie')))
            return $this->$property_name;
        else throw new Exception('不存在的属性:'.$property_name.'!');
    }
}