<?php

declare(strict_types = 1);
namespace Gaara\Core\Controller\Traits;

use Response;
use Closure;
use Gaara\Core\Cache;
use Gaara\Core\Template;
use Gaara\Core\Secure;
use InvalidArgumentException;

/**
 * 视图相关
 */
trait ViewTrait {

    // 页面加载地址
    protected $view = '';
    // 页面渲染语言种类
    protected $language = 0;
    // 页面渲染语言array
    protected $language_array = null;
    // 缓存js赋值
    protected $view_data_js = ';';
    // 缓存php赋值
    protected $view_data_php = [];
    // title
    protected $title = '';
    // 调用$this->display()时,引入的js
    protected $js = [];
    // css
    protected $css = [];

    /**
     * javascript 语句设置
     * @param string $word
     * @return void
     */
    protected function script(string $word): void {
        $this->view_data_js .= $word . ';';
    }

    /**
     * 将数据赋值到页面js 支持 int string array bool
     * @param string $name js对应键
     * @param mixed $val js对应值
     * @return void
     * @throws InvalidArgumentException
     */
    protected function assign(string $name, $val): void {
        $type = gettype($val);
        switch ($type) {
            case 'boolean':
                if ($val === true)
                    $this->view_data_js .= 'var ' . $name . '=true;';
                elseif ($val === false)
                    $this->view_data_js .= 'var ' . $name . '=false;';
                break;
            case 'integer':
                $this->view_data_js .= 'var ' . $name . '=' . $val . ';';
                break;
            case 'string':
                $this->view_data_js .= "var " . $name . "='" . $val . "';";
                break;
            case 'array':
                $this->view_data_js .= "var " . $name . "=" . json_encode($val) . ";";
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
        $this->view_data_php[$key] = $val;
    }

    /**
     * 定义视图title
     * @param string $title
     * @return void
     */
    protected function title(string $title = null): void {
        $this->title = $title;
    }
    
    /**
     * 定义将要引入的js文件
     * 第三方js文件,请使用绝对网路路径(以 http:// or https:// 开始)
     * @param string $file
     * @return void
     */
    protected function js(string $file): void {
        $this->js[] = (0 === strpos($file, 'http://') || 0 === strpos($file, 'https://')) ? $file : HOST . $file;
    }

    /**
     * 定义将要引入的css文件
     * 第三方css文件,请使用绝对网路路径(以 http:// or https:// 开始)
     * @param string $file
     * @return void
     */
    protected function css(string $file): void {
        $this->css[] = (0 === strpos($file, 'http://') || 0 === strpos($file, 'https://')) ? $file : HOST . $file;
    }

    /**
     * 生成视图数据
     * @param string $file 模版文件名
     * @return string
     */
    protected function display(string $file = null): string {
        ob_start();

        $this->html(function()use($file) {
            $this->head(function() {
                $this->headGaara();
                $this->setLanguage();
                $this->assignment();
                $this->includeJs();
                $this->includeCss();
                $this->setTitle();
            });
            $this->body(function()use($file) {
                $this->bodyFile($file);
            });
        });

        $contents = ob_get_contents();
        ob_end_clean();
        return Response::setContentType('html')->returnData($contents);
    }

    /**
     * 生成分块视图数据
     * @param string $file 模版文件名
     * @param string $note 模版注释
     * @return string
     */
    protected function template(string $file = null, string $note = null): string {
        ob_start();
        $note = $note ?? static::class.'::'.debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];
        $this->notes(function()use($file){
            $this->setLanguage();
            $this->includeJs();
            $this->includeCss();
            $this->assignment();
            $this->bodyFile($file);
        }, $note);

        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    
    /**
     * 在视图中引入静态视图组件
     * @param string $file
     * @return void
     */
    protected function includeHtml(string $file): void{
        include ROOT.$this->view.rtrim($file, '.html').'.html';
    }

    /**
     * 输出html标签
     * @param Closure $callback
     * @return void
     */
    protected function html(Closure $callback): void {
        echo '<!DOCTYPE html>';
        $callback();
        echo '</html>';
    }

    /**
     * 输出head标签
     * @param Closure $callback
     * @return void
     */
    protected function head(Closure $callback): void {
        echo <<<EEE
<head>
<html lang="zh-CN" xml:lang='zh-CN' xmlns='http://www.w3.org/1999/xhtml'>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
EEE;
        $callback();
        echo '</head>';
    }

    /**
     * 输出body标签
     * @param Closure $callback
     * @return void
     */
    protected function body(Closure $callback): void {
        echo '<body>';
        $callback();
        echo '</body>';
    }
    
    /**
     * 输出一个代码块
     * @param Closure $callback
     * @param string $note 注释
     * @return void
     */
    protected function notes(Closure $callback, string $note = 'Undefined note'): void {
        echo '<!-- '.$note.' start -->';
        $callback();
        echo '<!-- '.$note.' end -->';
    }

    /**
     * 定义gaara框架所需的js组件
     * js赋值, 由$this->assgin() or $this->script 定义
     * 由$this->display()调用一次
     * @return void
     */
    protected function headGaara(): void {
        // gaara js 引入
        echo DEBUG ? obj(Template::class)->includeFiles() : obj(Cache::class)->call(obj(Template::class), 'includeFiles', 30);
        // gaara js 定义
        $this->script(obj(Secure::class)->csrfAjax());
        $this->assign('HOST', HOST);
        $this->assign('contr', static::class);
        $this->script('$.extend({language:' . $this->language . '});');
    }

    /**
     * 输出js引入标签
     * 由$this->js() or $this->js 定义
     * 由$this->display()调用一次
     * @return void
     */
    protected function includeJs(): void {
        foreach ($this->js as $v) {
            echo '<script src="', $v, '"></script>';
        }
        $this->js = [];
    }

    /**
     * 输出css引入标签
     * 由$this->css() or $this->css 定义
     * 由$this->display()调用一次
     * @return void
     */
    protected function includeCss(): void {
        foreach ($this->css as $v) {
            echo '<link rel="stylesheet" type="text/css" href="', $v, '" />';
        }
        $this->css = [];
    }
    
    /**
     * 赋值多语言数组到页面
     * @return void
     */
    protected function setLanguage(): void {
        if (!is_null($this->language_array)) {
            $this->script('$.extend({language_json:' . json_encode($this->language_array, JSON_UNESCAPED_UNICODE) . '});');
        }
    }

    /**
     * 输出title标签
     * 由$this->title() or $this->title 定义
     * 由$this->display()调用一次
     * @return void
     */
    protected function setTitle(): void {
        echo '<title>', $this->title, '</title>';
    }
    
    /**
     * 输出页面js赋值
     * 由$this->assgin() or $this->script 定义
     * 由$this->display()调用一次
     * @return void
     */
    protected function assignment(): void {
        echo '<script>', $this->view_data_js, '</script>';
        $this->view_data_js = ';';
    }

    /**
     * 输出body文件内容
     * 由$this->display()调用一次
     * @param string $filename
     * @throws InvalidArgumentException
     * @return void
     */
    protected function bodyFile(string $filename = null): void {
        $file = ROOT . $this->view . ( $filename ?? substr(static::class, strrpos(static::class, '\\') + 1)) . '.html';
        if (is_file($file)) {
            extract($this->view_data_php);
            $this->view_data_php = [];
            unset($file);
            include ROOT . $this->view . ( $filename ?? substr(static::class, strrpos(static::class, '\\') + 1)) . '.html';
        } else
            throw new InvalidArgumentException($file . ' not found.');
    }

}
