<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class chatRoomContr extends Controller\HttpController{
    public function construct(){
//        $_SESSION['username'] = 'test!!!';
        var_dump($_SESSION);
    }
}