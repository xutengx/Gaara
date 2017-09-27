<?php

declare(strict_types = 1);
namespace Main\Core\Controller;

use \Closure;
use \PDOException;
use \Main\Core\Cache;
use \Main\Core\Template;
use \Response;
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
    // 页面加载地址
    protected $view = '';
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
        $this->classname = str_replace('Contr', '', end($app));

        defined('APP') || define('APP', $this->app);

        $this->construct();
    }

    public function construct() {
        
    }

    /**
     * @param int    $code   状态标记
     * @param string $msg  状态描述
     * @return bool
     */
    protected function returnMsg($code = '', $msg = 'fail !') {
        $data = ['code' => $code, 'msg' => $msg];
        return Response::returnData($data);
    }

    /**
     * @param  $content 响应内容
     * @param  $type_p  响应数据格式 json xml
     * @param  $code_p  响应 http 状态码
     * @return bool
     */
    protected function returnData($content = '') {
        if ($content instanceof Closure) {
            try{
                $content = call_user_func($content);
            }catch(PDOException $pdo){
                return $this->returnMsg(0, $pdo->getMessage());
            }
        }
        if ($content === false || $content === null || $content === 0 || $content === -1)
            return $this->returnMsg(0);
        $data = ['code' => 1, 'data' => $content];
        return Response::returnData($data);
    }

    // 以组件方式引入html
//    final protected function template($filename = false) {
//        $file = $filename ? $filename : $this->classname;
//        $this->assignPhp('T_VIEW', 'App/' . $this->app . '/View/');
//        $DATA = $this->phparray;
//        include ROOT . 'App/' . $this->app . '/View/template/' . $file . '.html';
//        echo '<script>' . $this->cache . '</script>';
//    }

    // 渲染页面赋值准备
    final protected function getReady() {
        $debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
        // 方法名,为调用此方法的最近级别函数
        $method = $debug[2]['function'];
        $this->assign('HOST', HOST);
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
        ob_start();
        echo <<<EEE
<!DOCTYPE html>
<html lang="zh-CN" xml:lang='zh-CN' xmlns='http://www.w3.org/1999/xhtml'>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
EEE;
        // 公用view DEBUG下不缓存
        echo DEBUG ? obj(Template::class)->includeFiles() : obj(Cache::class)->call(obj(Template::class), 'includeFiles', 30);
        // 页面各种赋值
        echo '<script>' . $ajax, $this->cache . '</script>';
        // 重置
        $this->cache = ';';
        if (file_exists(ROOT . $this->view . $file . '.html'))
            include ROOT . $this->view . $file . '.html';
        else
            throw new \Main\Core\Exception(ROOT . $this->view . $file . '.html' . '不存在!');
        echo <<<EEE
</html>
EEE;
        $contents = ob_get_contents();
        ob_end_clean();
        return Response::setContentType('html')->returnData($contents);
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
