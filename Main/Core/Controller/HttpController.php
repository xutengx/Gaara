<?php

declare(strict_types = 1);
namespace Main\Core\Controller;

use Closure;
use PDOException;
use Main\Core\Cache;
use Main\Core\Template;
use Main\Core\Secure;
use Response;
use InvalidArgumentException;

abstract class HttpController extends \Main\Core\Controller {

    // 可以使用 $this->post('id', '/^1[3|4|5|7|8][0-9]\d{8}$/', 'id不合法!'); 过滤参数
    use Traits\RequestTrait;

    // 可以使用 $this->getInfoOnWechatProfessional(); 一键授权(对数据库字段有一定要求)
    // use Traits\WechatTrait;

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
    // 当前Contr名
    protected $classname = NULL;

    public function __construct() {
        $app = explode('\\', get_class($this));
        $this->classname = str_replace('Contr', '', end($app));
        $this->construct();
    }

    public function construct() {
        
    }

    /**
     * 返回一个msg响应
     * @param int $code 状态标记
     * @param string $msg 状态描述
     * @return string
     */
    protected function returnMsg(int $code = 0, string $msg = 'fail !'): string {
        $data = ['code' => $code, 'msg' => $msg];
        return Response::returnData($data);
    }

    /**
     * 返回一个data响应,当接收的参数是Closure时,会捕获PDOException异常,一旦捕获成功,将返回msg响应
     * @param mixed $content 响应内容
     * @return string
     */
    protected function returnData($content = ''): string {
        if ($content instanceof Closure) {
            try {
                $content = call_user_func($content);
            } catch (PDOException $pdo) {
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

    /**
     * 渲染页面时的通用赋值
     * @return void
     */
    final protected function getReady(): void {
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

    /**
     * javascript 语句设置
     * @param string $word
     * @return void
     */
    final protected function script(string $word): void {
        $this->cache .= $word . ';';
    }

    /**
     * 写入script路由方法,js赋值,并引入html文件
     * @param string $filename 模版文件名
     * @return string
     * @throws \Main\Core\Exception
     */
    final protected function display(string $filename = null): string {
        $file = $filename ?? $this->classname;
        $this->getReady();
        $DATA = $this->phparray;
        // 防scrf的ajax(基于jquery), 将cookie中的X-CSRF-TOKEN, 加入ajax请求头
        $ajax = obj(Secure::class)->csrfAjax();

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
        if (is_file(ROOT . $this->view . $file . '.html'))
            include ROOT . $this->view . $file . '.html';
        else
            throw new InvalidArgumentException(ROOT . $this->view . $file . '.html not found.');
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
     * @param mixed $val js对应值
     * @return void
     * @throws \Main\Core\Exception
     */
    protected function assign(string $name, $val): void {
        $type = gettype($val);
        switch ($type) {
            case 'boolean':
                if ($val === true)
                    $this->cache .= 'var ' . $name . '=true;';
                elseif ($val === false)
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
                throw new InvalidArgumentException('Unsupported data types.');
        }
    }

    /**
     * 将数据赋值到页面php 以$DATA[$key]调用
     * @param string $key
     * @param mixed $val
     * @return void
     */
    protected function assignPhp(string $key, $val): void {
        $this->phparray[$key] = $val;
    }
}
