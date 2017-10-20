<?php

declare(strict_types = 1);
namespace Main\Core;

use Exception;
use Main\Core\Request\Traits;
use Main\Core\Request\UploadFile;

class Request {

    use Traits\ClientInfo;
    use Traits\RequestInfo;
    use Traits\Filter;

    private $domain = array();
    private $post = array();
    private $get = array();
    private $put = array();
    private $delete = array();
    private $cookie = array();
    private $input = array();
    private $file = null;
    // 当前http方法
    public $method;
    // 当前路由别名
    public $alias;
    // 当前路由可用的http方法数组
    public $methods;

    /**
     * 初始化参数
     * @param array $urlPar 路由参数数组
     * @param array $domainPar 域名参数数组
     */
    final public function __construct(array $urlPar = [], array $domainPar = []) {
        $this->file = new UploadFile();
        $this->getContentType($urlPar, $domainPar);
    }

    /**
     * 获取参数到当前类的属性
     * @param array $urlPar     来自路由的 url 参数
     * @param array $domainPar  来自路由的 域名 参数
     * @return void
     */
    private function getContentType(array $urlPar, array $domainPar): void {
        $this->domain = $this->filter($domainPar);
        $this->get = $this->filter($urlPar);
        $this->cookie = $this->_htmlspecialchars($_COOKIE);
        $this->method = strtolower($_SERVER["REQUEST_METHOD"]);

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
                $this->{$argc} = !empty($_POST) ? $this->_htmlspecialchars($_POST) : $this->filter($this->getStream($temp));
            }
        }
        $this->get = array_merge($this->get, $this->_htmlspecialchars($_GET));
        $this->consistentFile();
        $this->input = $this->{$argc};
    }

    /**
     * 设置cookie, 即时生效
     * @param string $name
     * @param type $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return void
     */
    public function setcookie(string $name, $value = '', int $expire = 0, string $path = '', string $domain = '', bool $secure = false, bool $httponly = true): void {
        $expire += time();
        $this->cookie[$name] = $_COOKIE[$name] = $value;
        if (is_array($value))
            foreach ($value as $k => $v)
                setcookie($name . '[' . $k . ']', $v, $expire, $path, $domain, $secure, $httponly);
        else
            setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * 分析stream获得数据, put文件上传时,php不会帮忙解析信息,只有手动了
     * @param string $input
     * @return array
     */
    private function getStream(string $input): array {
        $a_data = array();
        // grab multipart boundary from content type header
        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);

        // content type is probably regular form-encoded
        if (!count($matches)) {
            // we expect regular puts to containt a query string containing data
            parse_str(urldecode($input), $a_data);
            return $a_data;
        }

        // split content by boundary and get rid of last -- element
        $a_blocks = preg_split("/-+$matches[1]/", $input);
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
                // 兼容无文件上传的情况
                if (empty($matches))
                    continue;
                $content_blob = $matches[4];
                $content_blob = substr($content_blob, 0, strlen($content_blob) - strlen(PHP_EOL) * 2);        // 移除尾部多余换行符
                $this->file->addFile([
                    'key_name' => $matches[1],
                    'name' => $matches[2],
                    'type' => $matches[3],
                    'size' => strlen($content_blob),
                    'content' => $content_blob
                ]);
            }
            // parse all other fields
            else {
                // match "name" and optional value in between newline sequences
                preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
                $a_data[$matches[1]] = $matches[2] ?? '';
            }
        }
        return $a_data;
    }

    /**
     * 将$_FILES 放入 $this->file
     * @return void
     */
    private function consistentFile(): void {
        if (!empty($_FILES)) {
            foreach ($_FILES as $k => $v) {
                if ($v['error'] === 0){
                    $this->file->addFile([
                        'key_name' => $k,
                        'name' => $v['name'],
                        'type' => $v['type'],
                        'tmp_name' => $v['tmp_name'],
                        'size' => $v['size']
                    ]);
                }
            }
        }
    }

    /**
     * _addslashes, _htmlspecialchars
     * @param array $arr
     * @return array
     */
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
     * 获取原始数据数组
     * @param $property_name
     * @return mixed
     * @throws Exception
     */
    public function __get($property_name) {
        if (in_array(strtolower($property_name), array('input', 'post', 'get', 'put', 'cookie', 'delete', 'file'), true))
            return $this->$property_name;
        elseif (method_exists($this, $method = 'get' . ucfirst($property_name))) {
            return $this->$method();
        }
    }

    /**
     * 后期添加对应属性
     * @param string $property_name
     * @param mixed $value
     * @return void
     */
    public function __set(string $property_name, $value): void {
        if (in_array(strtolower($property_name), array('input', 'post', 'get', 'put', 'cookie', 'delete', 'file'), true)) {
            throw new Exception($property_name . ' should not be modified.');
        } else {
            $this->{$property_name} = $value;
        }
    }

}
