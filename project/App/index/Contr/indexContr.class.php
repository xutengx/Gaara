<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends Controller\HttpController{
    private $user;
    public function construct(){
//        $this->user = obj('userObj')->init(obj('userModel'));
    }
    public function indexDo(){
//        $obj = new \Main\Core\DbConnection('127.0.0.1', 3306, 'root', 'root', 'hk', $charset = 'utf8');
//        $re = $obj->where(['id'=>11])->from('hk_user')->select()->query();
//        var_dump($re);exit;
        $user = obj('userModel')->where('id=1')->where(['account'=>'a23652369'])->where(['id'=>['>',11]])->where(['id'=>['<',21]])
            ->where(['id'=>'15'])->where('sex=man')->where(['account'=>['>','1234']])
        ;
        var_dump($user);




//        \Main\Core\Loader::showObj();
//        $this->assignPhp('account',$this->user->account);
//        $this->display();
    }

//    public function __destruct(){
//        statistic();
//    }
}