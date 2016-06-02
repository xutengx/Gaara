<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class chatRoomContr extends Controller\HttpController{
    public function construct(){
        session_start();
//        phpinfo();
        $_SESSION['account'] = 'yyyy';
        $_SESSION['passwd'] = 123;
//        var_dump($_SESSION);
    }
}