<?php

/**
 * 引入html公用组件, 原则上动态赋值应该有且仅有$_SESSION & $_COOKIE 及公用值(如时间,天气)
 * User: Administrator
 * Date: 2016/1/21 0021
 * Time: 19:17
 */

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
    // 自动压缩后js 存放的目录
    const dataDir = 'data/minJS/';

    public function show($file) {
        include ROOT . 'App/' . APP . '/View/template/' . $file . '.html';
        return true;
    }

    /**
     * 跳转中间页
     * @param string $message
     * @param string $jumpUrl
     */
    public function jumpTo($message = '', $jumpUrl = 'index.php?path=index/index/indexDo/', $waitSecond = 3) {
        include ROOT . 'Main/Views/tpl/' . $this->jumpTo . '.html';
        exit;
    }

    /**
     * 自动加载静态文件 , 目前仅在控制器父类 HttpController->display() 中调用并缓存
     * @return string JS引入语句 (直接echo即可使用)
     */
    public function includeFiles() {
        $str = '';
        $str .= $this->createMin(self::jqueryDir);
        $str .=  '<script>jQuery.extend({inpath:"' . self::dataDir . '"});</script>';
        $str .= $this->createMin(self::jsDir);
        $this->createMin(self::pluginsDir);
        return $str;
    }

    /**
     * 生成压缩文件
     * @param string $originaDir    需要压缩的js所在目录
     * @param string $newDir        压缩后的js存放目录
     * 
     * @return string JS引入语句 (直接echo即可使用)
     */
    private function createMin($originaDir, $newDir = null) {
        $newDir = is_null($newDir) ? self::dataDir : $newDir;
        $files = obj(Tool::class)->getFiles($originaDir);
        $str = '';
        foreach ($files as $v) {
            $ext = strrchr($v, '.');
            if ($ext !== '.js')
                continue;
            $jsname = $newDir . str_replace($originaDir, '', $v);
            if (!file_exists($jsname) || filemtime($v) > filectime($jsname)) {
                $content = $this->AutomaticPacking(file_get_contents($v));
                obj('Tool')->printInFile($jsname, $content);
            }
            $str .= '<script src="' . HOST . $jsname . '"></script>';
        }
        return $str;
    }

    /**
     * 压缩js , 比较2种模式
     * @param string $content   压缩前js内容
     * @return string           压缩后js
     */
    private function AutomaticPacking($content) {
        $packerNormal = (new \Main\Core\JavaScriptPacker($content, 'Normal', false, false))->pack();
        $packerNone = (new \Main\Core\JavaScriptPacker($content, 'None', false, false))->pack();
        return strlen($packerNormal) > strlen($packerNone) ? $packerNone : $packerNormal;
    }
}
