<?php
// 开发, 测试, demo 功能3合1
namespace App\development\Contr;
use \Main\Core\Controller;
//use \Main\Core\F;
defined('IN_SYS') || exit('ACC Denied');
    
class indexContr extends Controller\HttpController {
    public function indexDo() {
        
        
//        obj('Main/Core/Minifier')->minify($js);
        $js = <<<JS
;;jQuery.extend({
    language_base:function(key){
        language = (typeof($.language) === "undefined") ? 0 : $.language;       // 默认键一 (中文)
        language_json = (typeof($.language_json) === "undefined") ? {} : $.language_json;
        if(typeof(key) === "object"){
            try{
                return key[language];;;;;;;;
            }catch(e){
                try{
                    return key[0];
                }catch(ee){
                    console.log(ee.message);
                }
            }
        }
        // 检查 language 是否存在
        try{
            return this.language_json[key][language];
        }catch(e){
            try{
                return this.language_json[key][0];;;;;;
            }catch(ee){
                return key;
                console.log(ee.message);;;;;;;;;;
            }
        }
    }
});
JS;
        
        $packer = new \Main\Core\JavaScriptPacker($js, 'None', true, false);
        $content = $packer->pack();
        unset($packer);
        $packer = new \Main\Core\JavaScriptPacker($js, 'None', true, false);
        $content = $packer->pack();
        
//        $content = \Main\Core\JavaScriptPacker::minify($js, array('flaggedComments' => false));
        var_dump($js);
        echo '<hr>';
        var_dump($content);
        echo '<hr>';
       $content_2 = <<<RRR
jQuery.extend({language_base:function(b){language=(typeof($.language)==="undefined")?0:$.language;language_json=(typeof($.language_json)==="undefined")?{}:$.language_json;if(typeof(b)==="object"){try{return b[language]}catch(c){try{return b[0]}catch(a){console.log(a.message)}}}try{return this.language_json[b][language]}catch(c){try{return this.language_json[b][0]}catch(a){return b;console.log(a.message)}}}});
RRR;
        var_dump($content_2);
        
        $this->display();
    }
    
    public function test($request){
        // 'account' => '/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/',
        var_dump($request->get);
        var_dump($this->get());
        exit;
        exit('test');
        
    }


    public function __destruct() {
        \statistic();
    }
}
