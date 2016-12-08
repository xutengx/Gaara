<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends Controller\HttpController{
    private $user = 'ttttttt';
    public function construct(){
//        $this->user = obj('userObj')->init('userModel');
    }
    public function indexDo(){
        $c = obj('cache');
        $re = obj('cache')->call($this,'cacheTest',1110 ,6);
//        $re = obj('cache')->get(true, function(){
//            return $this->cacheTest(3);
//            
//        },10);
            $c->set('qwe_123', 'qwe_123', 666);
            $c->set('qwe_1232', 'qwe_1232', 666);
            $c->set('qwe_124', 'qwe_124', 666);
            $c->set('qwe_126', 'qwe_126', 666);
        $c->clear($this, 'cacheTest');
        $re = obj('cache')->keys($c->redis->prefix.'*');
        var_dump($re);
        
    }
    
    protected function cacheTest($a = 1){
        sleep(3);
        echo 'ok';
        return 123+$a;
    }
}