<?php

declare(strict_types = 1);
namespace Main\Core\Request\Traits;

/**
 * 客户端相关信息获取
 */
trait Filter {

    private $filterArr = [
        'email' => '/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/',
        'email2' => '/^[0-9a-z][_.0-9a-z-]{0,31}@([0-9a-z][0-9a-z-]{0,30}[0-9a-z]\.){1,4}[a-z]{2,4}$/i',
        'url' => '/\b(([\w-]+:\/\/?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/)))/',
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
        // 大小写字母,数字,下划线,减号'-'
        'token' => '/^[\w-]+$/',
        // 大小写字母,数字,下划线
        'sign' => '/^{0,200}$/',
        // 2-8位
        'name' => '/^[_\w\d\x{4e00}-\x{9fa5}]{2,8}$/iu'
    ];

    public function get(string $key, $filter = false) {
        if (isset($this->get[$key]))
            return $filter ? $this->filterMatch($this->get[$key], $filter) : $this->get[$key];
        else
            return null;
    }

    public function post(string $key, $filter = false) {
        if (isset($this->post[$key]))
            return $filter ? $this->filterMatch($this->post[$key], $filter) : $this->post[$key];
        else
            return null;
    }

    public function put(string $key, $filter = false) {
        if (isset($this->put[$key]))
            return $filter ? $this->filterMatch($this->put[$key], $filter) : $this->put[$key];
        else
            return null;
    }

    public function delete(string $key, $filter = false) {
        if (isset($this->delete[$key]))
            return $filter ? $this->filterMatch($this->delete[$key], $filter) : $this->delete[$key];
        else
            return null;
    }

    public function cookie(string $key, $filter = false) {
        if (isset($this->cookie[$key]))
            return $filter ? $this->filterMatch($this->cookie[$key], $filter) : $this->cookie[$key];
        else
            return null;
    }

    /**
     * 获取当前请求类型的参数
     * @param string $key
     * @param string $filter 预定规则 or 正则表达式
     * @return type
     */
    public function input(string $key, $filter = false) {
        $method = $this->method;
        if (isset($this->{$method}[$key]))
            return $filter ? $this->filterMatch($this->{$method}[$key], $filter) : $this->{$method}[$key];
        else
            return null;
    }
    
    /**
     * 获取请求头中的内容
     * @param string $key
     */
    public function header(string $key){
        return $_SERVER[$key] ?? null;
    }

    /**
     * 外部获取 预定义验证规则
     * @return array
     */
    public function getFilterArr(): array {
        return $this->filterArr;
    }

    /**
     * 正则匹配
     * @param string $str    检验对象
     * @param string $filter 匹配规则
     * @return mixed or false
     */
    private function filterMatch(string $str, string $filter) {
        $filter = array_key_exists($filter, $this->filterArr) ? $this->filterArr[$filter] : $filter;
        return preg_match($filter, $str) ? $str : false;
    }
}
