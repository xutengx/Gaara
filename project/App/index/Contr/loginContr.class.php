<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');

/**
 * 微信授权
 * Class loginContr
 * @package App\index\Contr
 */
class loginContr extends Controller\HttpController{
    public function indexDo(){
       if($this->main_checkSessionUser())
           headerTo('index/index/indexDo');
    }
}