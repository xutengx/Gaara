<?php
declare(strict_types=1);

namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends Controller\HttpController{
    private $user;
    public function construct(){
        $this->user = obj('userObj')->init(obj('userModel'));
    }
    public function indexDo(){
//        session_start();
//        $_SESSION['e'] = 'ee';
//        var_dump($_SESSION['e']);
        $this->assignPhp('account',$this->user->account);
        $this->display();
    }
    public function add(int $b):array{
        $arr = [];
        for($i = 2; $i < $b; $i++) {
            $primes = 0;
            for($k = 1; $k <= $i; $k++)
                if($i%$k === 0) $primes++;
            if($primes <= 2) // 能除以1和自身的整数(不包括0)
                $arr[] = $i;
        }
        return $arr;
    }
    public function add2($b){
        $arr = [];
        for($i = 2; $i < $b; $i++) {
            $primes = 0;
            for($k = 1; $k <= $i; $k++)
                if($i%$k === 0) $primes++;
            if($primes <= 2) // 能除以1和自身的整数(不包括0)
                $arr[] = $i;
        }
        return $arr;
    }
    public function __destruct(){
        statistic();
    }
}