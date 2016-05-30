<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends Controller\HttpController{
    public function indexDo(){
//        var_dump(obj('userModel')->selRow(1));
        obj('qweqeContr');
    }
}