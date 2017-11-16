<?php

namespace App\Dev\inifile;
use Gaara\Core\Request;
use Cache;
class index extends \Gaara\Core\Controller{
    
    public function index(Request $request){
        // 值为 null，no 和 false 等效于 ""，值为 yes 和 true 等效于 "1"。
        // 字符 {}|"~![()" 也不能用在键名的任何地方，而且这些字符在选项值中有着特殊的意义。
        $tmp = parse_ini_file(ROOT.".env", true);
//        var_dump($tmp);exit;
        $env = $tmp['ENV'];
        $tmp = array_merge($tmp, $tmp[$env]);        
        var_dump($tmp);exit;
    }
    

}