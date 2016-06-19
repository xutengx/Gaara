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
//        $user = obj('userModel')
//            ->where(['account'=>'a23652369'])->where(['id'=>['>',11]])->where(['id'=>['<',21]])
//            ->where(['id'=>'15'])->where(['account'=>['>','1234']])->where(['sex'=>''])
//            ->select(['account','sex'])->select('name')->select('name as a')->select(['account','sex as s'])
//            ->getRow();
//        $user = obj('userModel')->where('id=:id')->select('id')->prepare() ;
//        $user->execute();
//        var_dump($user->execute([':id'=>11]));
        var_dump(obj('userModel')
//            ->where(['id'=>['not between',11,':asd']])
////            ->where('id=weqwe')
//            ->where(['id'=>['in',[1,11,12,14]]])
//            ->where(['id'=>['not like',':ee']])
                ->from('hk_user as a',true)
            ->getAll());

//        $user = obj('userModel')->where('id=:id')->select('id,account')->prepare() ;
//
//        var_dump($user->execute([':id'=>13]));
//        var_dump($user->execute());
//        var_dump($user->execute());

//        var_dump(obj('userModel')->filterColumn('count(qweqw) aS 1fff'));




//        \Main\Core\Loader::showObj();
//        $this->assignPhp('account',$this->user->account);
//        $this->display();
    }

//    public function __destruct(){
//        statistic();
//    }
}