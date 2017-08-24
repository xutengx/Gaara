<?php

namespace Main\Core;
defined('IN_SYS') || exit('ACC Denied');

use \Main\Core\Request\Traits;
use \Main\Core\Request\File;

class Request {

    use Traits\ClientInfo;
    use Traits\RequestInfo;
    use Traits\File;

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
        // 大小写字母,数字,下划线
        'sign' => '/^{0,200}$/',
        // 2-8位
        'name' => '/^[_\w\d\x{4e00}-\x{9fa5}]{2,8}$/iu'
    );

    final public function __construct(array $urlPar = [], array $domainPar = []) {
        $this->getContentType($urlPar, $domainPar);
    }

    private function getContentType(array $urlPar, array $domainPar) {
        $this->domain = $this->filter($domainPar);
        $this->get = $this->filter($urlPar);
        $this->cookie = $this->_htmlspecialchars($_COOKIE);

        $this->method = $this->getMethod();

        if (($argc = $this->method) !== 'get') {
            $temp = file_get_contents('php://input');
            $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';

            if (stripos($content_type, 'application/x-www-form-urlencoded') !== false) {

                parse_str($temp, $this->{$argc});
                $this->{$argc} = $this->filter($this->{$argc});
            } elseif (stripos($content_type, 'application/json') !== false) {

                $this->{$argc} = json_decode($temp, true);
            } elseif (stripos($content_type, 'application/xml') !== false) {

                $this->{$argc} = obj(Tool::class)->xml_decode($temp);
            } else {
                $this->{$argc} = $_POST ? empty($this->_htmlspecialchars($_POST)) : $this->filter($this->getStream($temp));
            }
        }

        $this->get = array_merge($this->get, $this->_htmlspecialchars($_GET));
        $this->consistentFile();
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
    public function input(string $key, $filter = false) {
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

    private function filter(array $arr): array {
        return $this->_addslashes($this->_htmlspecialchars($arr));
    }

    /**
     * 在预定义字符之前添加反斜杠, 预定义字符是：单引号（'）,双引号（"）, 反斜杠（\）, NULL
     * 默认地，PHP 对所有的 GET、POST 和 COOKIE 数据自动运行 addslashes()。
     * 所以您不应对已转义过的字符串使用 addslashes()，因为这样会导致双层转义。
     * 遇到这种情况时可以使用函数 get_magic_quotes_gpc() 进行检测
     * @param array $arr
     * @return array
     */
    private function _addslashes(array $arr): array {
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

    /**
     * 预定义的字符转换为 HTML 实体, 预定义的字符是：& （和号）, " （双引号）, ' （单引号）,> （大于）,< （小于）
     * @param array $arr
     * @return array
     */
    private function _htmlspecialchars(array $arr): array {
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
    

    /**
     * 分析stream获得数据, 主要用于put文件上传
     * @param string $input
     * @return array
     */
    private function getStream(string $input) : array {
        $a_data = array();
        // grab multipart boundary from content type header
        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);

        // content type is probably regular form-encoded
        if (!count($matches)) {
            // we expect regular puts to containt a query string containing data
            parse_str(urldecode($input), $a_data);
            return $a_data;
        }
        $boundary = $matches[1];

        // split content by boundary and get rid of last -- element
        $a_blocks = preg_split("/-+$boundary/", $input);
        array_pop($a_blocks);

        // loop data blocks
        foreach ($a_blocks as $block) {
            if (empty($block))
                continue;
            // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char
            // parse uploaded files
            if (strpos($block, 'filename=') !== FALSE) {
                // match "name", then everything after "stream" (optional) except for prepending newlines
                preg_match("/name=\"([^\"]*)\".*filename=\"([^\"].*?)\".*Content-Type:\s+(.*?)[\n|\r|\r\n]+([^\n\r].*)?$/s", $block, $matches);
                $key_name = $matches[1];
                $content_blob = $matches[4];
                $content_blob = substr($content_blob,0,strlen($content_blob)-strlen(PHP_EOL)*2);        // 移除尾部多余换行符
                
                $file = new File;
                $file->name = $matches[2];
                $file->key_name = $key_name;
                $file->type = $matches[3];
                $file->content = function() use ($content_blob){
                    return $content_blob;
                };
                $file->size = strlen($content_blob);
                
                $this->file[$key_name] = $file;
            }
            // parse all other fields
            else {
                // match "name" and optional value in between newline sequences
                preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
                $a_data[$matches[1]] = $matches[2];
            }
        }
        return $a_data;
    }
    /**
     * 将$_FILES 放入 $this->file
     */
    private function consistentFile(){
        if(!empty($_FILES)){
            foreach ($_FILES as $k => $v) {
                $file = new File;
                $file->name = $v['name'];
                $file->key_name = $k;
                $file->type = $v['type'];
                $file->tmp_name = $v['tmp_name'];
                $file->content = function() use ($v){
                    return file_get_contents($v['tmp_name']);
                };
                $file->size = $v['size'];
                
                $this->file[$k] = $file;
            }
        }
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
        if (in_array(strtolower($property_name), array('input', 'post', 'get', 'put', 'delete', 'cookie', 'file')))
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
        if (in_array(strtolower($property_name), array('input', 'post', 'get', 'put', 'delete', 'cookie', 'file'))) {
            throw new Exception($property_name . ' 不应该被修改');
        } else {
            $this->{$property_name} = $value;
            return true;
        }
    }
}
