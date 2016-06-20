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
//        $str =' order by `asda` asc';
//        echo preg_match('#\s(asc|desc)$#i', $str) ? 'yyyyyyy' : 'nnnnnnnn' ;
        var_dump(obj('userModel')
            ->data(['name'=>'test','sex'=>1])
            ->where(['id'=>17])
            ->replace(true));

//
//        \Main\Core\Loader::showObj();
//        $this->assignPhp('account',$this->user->account);
//        $this->display();
    }

//    public function __destruct(){
//        statistic();
//    }
}