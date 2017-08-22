<?php

namespace Main\Core;
defined('IN_SYS') || exit('ACC Denied');

use \Main\Core\Request\Traits;

class Request {

    use Traits\ClientInfo;
    use Traits\RequestInfo;

    private $domain = array();
    private $post = array();
    private $get = array();
    private $put = array();
    private $delete = array();
    private $cookie = array();
    private $input = array();
    private $filterArr = array(
        'email' => '/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/',
        'email2' => '/^[0-9a-z][_.0-9a-z-]{0,31}@([0-9a-z][0-9a-z-]{0,30}[0-9a-z]\.){1,4}[a-z]{2,4}$/i',
        'url' => '/^[a-zA-z]+://(\w+(-\w+)*)(\.(\w+(-\w+)*))*(\?\S*)?$/',
        'int' => '/^-?\d+$/',
        // 密码(允许5-32字节，允许字母数字下划线)
        'passwd' => '/^[\w]{5,32}$/',
        // 帐号(字母开头，允许5-16字节，允许字母数字下划线)
        'account' => '/^[a-zA-Z][a-zA-Z0-9_]{5,16}$/',
        // 邮编
        'mail' => '/^[1-9]\d{5}$/',
        // 中国手机号码：(86)*0*13\d{9}
        'phone' => '/^1[3|4|5|7|8][0-9]\d{8}$/',
        // 中国手机号码：(86)*0*13\d{9}
        'tel' => '/^1[3|4|5|7|8][0-9]\d{8}$/',
        // 大小写字母,数字,下划线
        'string' => '/^\w+$/',
        // 大小写字母,数字,下划线
        'sign' => '/^{0,200}$/',
        // 2-8位
        'name' => '/^[_\w\d\x{4e00}-\x{9fa5}]{2,8}$/iu'
    );

    final public function __construct($urlPar = [], $domainPar = []) {
        $this->getContentType($urlPar, $domainPar);
    }

    private function getContentType($urlPar, $domainPar) {
        $this->domain = $this->_addslashes($this->_htmlspecialchars($domainPar));
        $this->get = $this->_addslashes($this->_htmlspecialchars($urlPar));
        $this->cookie = $this->_addslashes($this->_htmlspecialchars($_COOKIE));
        $this->getMethod();
        if (($argc = strtolower($_SERVER['REQUEST_METHOD'])) != 'get') {
            $this->{$argc} = file_get_contents('php://input');
            $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
            if(stripos($content_type, 'application/x-www-form-urlencoded') !== false){
                parse_str($this->{$argc}, $this->{$argc});
                $this->{$argc} = $this->_addslashes($this->_htmlspecialchars($this->{$argc}));
            }elseif(stripos($content_type, 'application/json')!== false){
                $this->{$argc} = json_decode($this->{$argc}, true);
            }elseif(stripos($content_type, 'application/xml')!== false){
                $this->{$argc} = obj(Tool::class)->xml_decode($this->{$argc});
            }else{
                $this->{$argc} = $_POST ? $_POST : $this->{$argc};
            }
        } else {
            $this->get = array_merge($this->get, $this->_addslashes($this->_htmlspecialchars($_GET)));
        }
        $this->input = $this->{$argc};
    }

    public function get($key, $filter = false) {
        if (isset($this->get[$key]))
            return $filter ? $this->filterMatch($this->get[$key], $filter) : $this->get[$key];
        else
            return null;
    }

    public function post($key, $filter = false) {
        if (isset($this->post[$key]))
            return $filter ? $this->filterMatch($this->post[$key], $filter) : $this->post[$key];
        else
            return null;
    }

    public function put($key, $filter = false) {
        if (isset($this->put[$key]))
            return $filter ? $this->filterMatch($this->put[$key], $filter) : $this->put[$key];
        else
            return null;
    }

    public function delete($key, $filter = false) {
        if (isset($this->delete[$key]))
            return $filter ? $this->filterMatch($this->delete[$key], $filter) : $this->delete[$key];
        else
            return null;
    }

    public function cookie($key, $filter = false) {
        if (isset($this->cookie[$key]))
            return $filter ? $this->filterMatch($this->cookie[$key], $filter) : $this->cookie[$key];
        else
            return null;
    }

    public function session($key, $filter = false) {
        if (isset($_SESSION[$key]))
            return $filter ? $this->filterMatch($_SESSION[$key], $filter) : $_SESSION[$key];
        else
            return null;
    }
    /**
     * 获取当前请求类型的参数
     * @param string $key
     * @param type $filter
     * @return type
     */
    public function input(string $key, $filter = false){
        $method = $this->method;
        if (isset($this->{$method}[$key]))
            return $filter ? $this->filterMatch($this->{$method}[$key], $filter) : $this->{$method}[$key];
        else
            return null;
    }

    public function set_cookie($k, $v) {
        $this->cookie[$k] = $v;
    }

    /**
     * 正则匹配
     * @param string $str    检验对象
     * @param string $filter 匹配规则
     * @return mixed or false
     */
    private function filterMatch($str, $filter) {
        $filter = array_key_exists($filter, $this->filterArr) ? $this->filterArr[$filter] : $filter;
        return preg_match($filter, $str) ? $str : false;
    }

    private function _addslashes($arr) {
        $q = array();
        foreach ($arr as $k => $v) {
            if (is_string($v)) {
                $q[addslashes($k)] = addslashes($v);
            } else if (is_array($v)) {
                $q[addslashes($k)] = $this->_addslashes($v);
            }
        }
        return $q;
    }

    private function _htmlspecialchars($arr) {
        $q = array();
        foreach ($arr as $k => $v) {
            if (is_string($v)) {
                $q[($k)] = htmlspecialchars($v);
            } else if (is_array($v)) {
                $q[($k)] = $this->_htmlspecialchars($v);
            }
        }
        return $q;
    }

    // 外部获取 预定义验证规则
    public function getFilterArr() {
        return $this->filterArr;
    }

    /**
     * 获取原始数据数组
     * @param $property_name
     *
     * @return mixed
     * @throws Exception
     */
    public function __get($property_name) {
        if (in_array(strtolower($property_name), array('input','post', 'get', 'put', 'delete', 'cookie')))
            return $this->$property_name;
        elseif (method_exists($this, $method = 'get' . ucfirst($property_name))) {
//            return call_user_func($method);
            return $this->$method();
        } else {
            $key = strtoupper($property_name);
            return isset($_SERVER[$key]) ? $_SERVER[$key] : (isset($_SERVER['HTTP_' . $key]) ? $_SERVER['HTTP_' . $key] : null);
        }
    }
    
    /**
     * 后期添加对应属性
     * @param string $property_name
     * @param type $value
     */
    public function __set(string $property_name, $value) {
        if (in_array(strtolower($property_name), array('input','post', 'get', 'put', 'delete', 'cookie'))){
            throw new Exception($property_name.' 不应该被修改');
        }else{
            $this->{$property_name} = $value;
            return true;
        }
    }
}
