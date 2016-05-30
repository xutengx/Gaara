<?php
namespace App\index\Controller;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class testContr extends Controller\HttpController{
    private $me = null;
    public function construct(){
//        $this->me = obj('userObj')->init(obj('userModule'), 1);
//        $this->me = obj('cache')->cacheCall(obj('userObj'),'init',true,obj('userModule'), 1);
    }
    public function indexDo(){
        echo 'this is testContr !!';
        statistic();
    }
//    public function ttt(){
//        obj('cache')->cacheClear();
//    }
    public function get(array $data){
        echo 'i get !!!';
    }
    public function put(array $data){
        echo 'i put !!!';
    }
    public function post(array $data){
        echo 'i post !!!';
    }
    public function delete(array $data){
        echo 'i delete !!!';
    }
}