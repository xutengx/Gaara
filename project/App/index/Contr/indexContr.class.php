<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends Controller\HttpController{
    private $me = null;
    public function construct(){
//        var_dump(func_get_args());
        $this->me = obj('userObj')->init(obj('userModel'), 1);
//        $this->me = obj('cache')->cacheCall(obj('userObj'),'init',true,obj('userModule'), 1);
    }
    public function indexDo(){
        $d = $this->tttt();
        var_dump($d['func']());
    }
    protected function show(){
        $this->display('index2');
    }
    protected function t($int){
        echo $int;
    }
    private function tttt(){
        return array(
            'a' => 'aa',
            'b' => 'bb',
            'func' => function(){
                return 'this is func';
            }
        );
    }
}