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
        session_start();
//        $_SESSION['e'] = 'ee';
        var_dump($_SESSION['e']);

//        $this->assignPhp('account',$this->user->account);
//        $this->display();
    }
}