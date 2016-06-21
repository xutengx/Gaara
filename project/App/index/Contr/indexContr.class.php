<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends Controller\HttpController{
    private $user;
    public function construct(){
//        var_dump($_SESSION);exit;
        $this->user = obj('userObj')->init('userModel');
    }
    public function indexDo(){
//        unset($_SESSION['openid']);
        echo 'index';
    }
}