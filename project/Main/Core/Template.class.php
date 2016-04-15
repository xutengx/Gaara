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
    private   $jumpTo =  'jumpTo';
    // js引入
    const jsDir =  'Main/Views/include/js';
    // js min版本引入
    const minjsDir =  'Main/Views/include/minjs';
    // jquery引入
    const jqueryDir =  'Main/Views/include/jquery';

    public   function show($file){
        include ROOT.'Application/'.APP.'/View/template/'.$file.'.html';
        return true;
    }
    // 跳转中间页
    public function jumpTo($message, $jumpUrl='index?path=index/index/indexDo/'){
        $waitSecond = 3;
        include ROOT.'Main/Views/tpl/'.$this->jumpTo.'.html';
        exit;
    }
    // 自动加载静态文件
    public function includeFiles(){
        $this->includeFile(self::jqueryDir);
        MINJS ? $this->includeFile(self::minjsDir) : $this->includeFile(self::jsDir);
    }
    private function includeFile($dir){
        $files = obj('Tool')->getFiles($dir);
        $str = '';
        foreach($files as $k=>$v){
            $ext = strrchr($v , '.');
            switch ($ext) {
                case '.js' :
                    $str .= '<script src="'.$v.'"></script>';
                    break;
                case '.css' :
                    $str .= '<link rel="stylesheet" href="'.$v.'" />';
                    break;
                case '.ico' :
                    $str .= '<link rel="shortcut icon" href="'.$v.'" type="image/x-icon">';
                    break;
                default:
                    break;
            }
        }
        echo $str;
    }
}