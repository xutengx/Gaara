<?php

namespace Main\Core\Controller;

defined('IN_SYS') || exit('ACC Denied');

/**
 * 响应页面
 * Class HttpController
 * @package Main\Core\Controller
 */
abstract class HttpController extends \Main\Core\Controller {

    // 可以使用 $this->post('id', '/^1[3|4|5|7|8][0-9]\d{8}$/', 'id不合法!'); 过滤参数
    use Traits\RequestTrait;

    // 可以使用 $this->getInfoOnWechatProfessional(); 一键授权(对数据库字段有一定要求)
    // use Traits\WechatTrait;
    // 页面过期时间  0 : 不过期
    protected $viewOverTime = 0;
    // 页面渲染语言种类
    protected $language = 0;
    // 页面渲染语言array
    protected $language_array = null;
    // 缓存js赋值
    protected $cache = ';';
    // 缓存php赋值
    protected $phparray = array();
    // 当前Contr所在app名
    protected $app = NULL;
    // 当前Contr名
    protected $classname = NULL;

    public function __construct() {
        $app = explode('\\', get_class($this));
        if ($app[0] == 'App') {
            $this->app = $app[1];
            $this->classname = str_replace('Contr', '', $app[3]);
        }
        $this->construct();
    }

    public function construct() {
        
    }

    // cookie即时生效
    protected function setcookie($var, $value = '', $time = 0, $path = '', $domain = '', $s = false) {
        $_COOKIE[$var] = $value;
        obj('F')->set_cookie($var, $value);
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                setcookie($var . '[' . $k . ']', $v, $time, $path, $domain, $s);
            }
        } else
            setcookie($var, $value, $time, $path, $domain, $s);
    }

    public function indexDo() {
        $this->display();
    }

    /**
     * @param int    $code   状态标记
     * @param string $msg  状态描述
     *
     * @return bool
     */
    protected function returnMsg($code = '', $msg = 'fail !') {
        $data = ['code' => $code , 'msg' => $msg ];
        return obj('\Main\Core\Response')->returnData($data, false, $code);
    }

    /**
     * @param  $content 响应内容
     * @param  $type_p  响应数据格式 json xml
     * @param  $code_p  响应 http 状态码
     * 
     * @return bool
     */
    protected function returnData($content = '', $type_p = false, $code_p = false) {
        if($content === false && $content === null && $content === 0 && $content === -1) return $this->returnMsg(0);
        if (is_int($type_p)) {
            $type = $code_p ? $code_p : false;
            $code = $type_p;
        } else {
            $type = $type_p;
            $code = $code_p;
        }
        $data = ['code' => 1 , 'data' => $content ];
        return obj('\Main\Core\Response')->returnData($data, $type, $code);
    }

    // 以组件方式引入html
    final protected function template($filename = false) {
        $file = $filename ? $filename : $this->classname;
        $this->assignPhp('T_VIEW', 'App/' . $this->app . '/View/');
        $DATA = $this->phparray;
        include ROOT . 'App/' . $this->app . '/View/template/' . $file . '.html';
        echo '<script>' . $this->cache . '</script>';
    }

    // 渲染页面赋值准备
    final protected function getReady() {
        $debug = debug_backtrace();
        // 方法名,为调用此方法的最近级别函数
        $method = $debug[1]['function'];
        $this->assign('path', PATH);
        $this->assign('in_sys', IN_SYS);
        $this->assign('view', VIEW);
        $this->assign('contr', $this->classname);
        $this->assign('method', $method);
        $this->script('$.extend({language:' . $this->language . '});');
        if (!is_null($this->language_array)) {
            $this->script('$.extend({language_json:' . json_encode($this->language_array, JSON_UNESCAPED_UNICODE) . '});');
        }
    }

    // javascript 设置
    final protected function script($word) {
        $this->cache .= $word . ';';
    }

    // 写入script路由方法,js赋值,并引入html文件
    final protected function display($filename = false) {
        $file = $filename ? $filename : $this->classname;
        $this->getReady();
        $DATA = $this->phparray;
        // 防scrf的ajax(基于jquery), 接受post提交数据前.先验证http头中的 csrftoken
        $ajax = obj('Secure')->csrfAjax($this->classname);
        // js 路由方法
        $str = 'function __url__(Application, Controller, method){if(arguments.length==1){method=Application;Controller="' . $this->classname . '";Application="' . $this->app . '";}else if(arguments.length==2) {method=Controller;Controller=Application;Application="' . $this->app . '";} var url=window.location.protocol+"//"+window.location.host+window.location.pathname+"?' . PATH . '="+Application+"/"+Controller+"/"+method+"/"; return url;}';
        echo <<<EEE
<!DOCTYPE html>
<html lang="zh-CN" xml:lang='zh-CN' xmlns='http://www.w3.org/1999/xhtml'>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
EEE;
        // 公用view DEBUG下不缓存
        DEBUG ? obj('Template')->includeFiles() : obj('cache')->call(obj('Template'), 'includeFiles',1);
        // 页面各种赋值
        echo '<script>' . $ajax, $this->cache, $str . '</script>';
        // 重置
        $this->cache = ';';
        if (file_exists(ROOT . 'App/' . $this->app . '/View/' . $file . '.html'))
            include ROOT . 'App/' . $this->app . '/View/' . $file . '.html';
        else
            throw new \Main\Core\Exception(ROOT . 'App/' . $this->app . '/View/' . $file . '.html' . '不存在!');
        echo <<<EEE
</html>
EEE;
        return true;
    }

    /**
     * 将数据赋值到页面js 支持 int string array bool
     * @param string $name js对应键
     * @param string $val  js对应值
     *
     * @throws \Main\Core\Exception
     */
    protected function assign($name = '', $val = '') {
        $type = gettype($val);
        switch ($type) {
            case 'boolean':
                if ($val === true)
                    $this->cache .= 'var ' . $name . '=true;';
                else if ($val === false)
                    $this->cache .= 'var ' . $name . '=false;';
                break;
            case 'integer':
                $this->cache .= 'var ' . $name . '=' . $val . ';';
                break;
            case 'string':
                $this->cache .= "var " . $name . "='" . $val . "';";
                break;
            case 'array':
                $this->cache .= "var " . $name . "=" . json_encode($val) . ";";
                break;
            default:
                throw new \Main\Core\Exception('暂不支持的数据类型!');
        }
    }

    /**
     * 将数据赋值到页面php 以$DATA[$key]调用
     * @param string $key
     * @param string $val
     */
    protected function assignPhp($key = '', $val = '') {
        $this->phparray[$key] = $val;
    }
}
