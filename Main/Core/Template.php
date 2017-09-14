<?php

declare(strict_types = 1);
namespace Main\Core;

class Template {

    // 跳转中间页面
    private $jumpTo = 'jumpTo';

    // jquery 引入
    const jqueryDir = 'Main/Views/include/jquery/';
    // js 引入
    const jsDir = 'Main/Views/include/js/';
    // js_plugins 引入
    const pluginsDir = 'Main/Views/include/plugins/';
    // js_plugins 所需的css 引入
    const pluginsCssDir = 'Main/Views/include/css/';
    // 自动压缩后js 存放的目录, public 下
    const dataDir = 'open/minStatic/';

    public function show(string $file) {
        include ROOT . 'App/' . APP . '/View/template/' . $file . '.html';
        return true;
    }
    /**
     * 跳转中间页
     * @param string $message
     * @param string $jumpUrl
     */
    public function jumpTo(string $url, string $message, int $waitSecond = 3) {
        include ROOT . 'Main/Views/tpl/' . $this->jumpTo . '.html';
        exit;
    }

    /**
     * 自动加载静态文件 , 目前仅在控制器父类 HttpController->display() 中调用并缓存
     * @return string JS引入语句 (直接echo即可使用)
     */
    public function includeFiles(): string {
        $str = '';
        $str .= $this->createMin(ROOT . self::jqueryDir);
        $str .= '<script>jQuery.extend({inpath:"' . self::dataDir . '"});</script>';
        $str .= $this->createMin(ROOT . self::jsDir);
        $this->createMin(ROOT . self::pluginsDir);
        $this->createMin(ROOT . self::pluginsCssDir);
        return $str;
    }

    /**
     * 生成压缩文件
     * @param string $originaDir    需要压缩的js所在目录
     * @param string $newDir        压缩后的js存放目录
     * 
     * @return string JS引入语句 (直接echo即可使用)
     */
    private function createMin(string $originaDir, $newDir = null): string {
        $newDir = is_null($newDir) ? self::dataDir : $newDir;
        $files = obj(Tool::class)->getFiles($originaDir);
        $str = '';
        foreach ($files as $v) {
            $ext = strrchr($v, '.');
            if ($ext === '.js') {
                $jsname = $newDir . str_replace($originaDir, '', $v);
                if (!file_exists($jsname) || filemtime($v) > filectime($jsname)) {
                    $content = $this->AutomaticPacking(file_get_contents($v));
                    obj(Tool::class)->printInFile($jsname, $content);
                }
                $str .= '<script src="' . HOST . $jsname . '"></script>';
            } elseif ($ext === '.css') {
                $jsname = $newDir . str_replace($originaDir, '', $v);
                if (!file_exists($jsname) || filemtime($v) > filectime($jsname)) {
                    $content = $this->compressCss(file_get_contents($v));
                    obj(Tool::class)->printInFile($jsname, $content);
                }
                $str .= '<link rel="stylesheet" type="text/css" href="' . HOST . $jsname . '" />';
            }
        }
        return $str;
    }

    /**
     * 压缩js , 比较2种模式
     * @param string $content   压缩前js内容
     * @return string           压缩后js
     */
    private function AutomaticPacking(string $content): string {
        $packerNormal = (new \Main\Core\JavaScriptPacker($content, 'Normal', false, false))->pack();
        $packerNone = (new \Main\Core\JavaScriptPacker($content, 'None', false, false))->pack();
        return strlen($packerNormal) > strlen($packerNone) ? $packerNone : $packerNormal;
    }

    /**
     * 压缩 css
     * @param string $content   压缩前 css 内容
     * @return string           压缩后 css
     */
    private function compressCss(string $content): string {
        /* remove comments */
        $content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
        /* remove tabs, spaces, newlines, etc. */
        $content = str_replace(array("
", "\r", "\n", "\t", '  ', '    ', '    '), '', $content);
        return $content;
    }
}
