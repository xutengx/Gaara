<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class apiContr extends Controller\HttpController{
    protected $session = false;
    public function construct(){
        $arg = func_get_args();
        obj($arg[0].'Api');
        statistic();
        exit;
    }
}