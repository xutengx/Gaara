<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class regContr extends Controller\HttpController{
    public function message(){
        $tel = $this->post('tel','tel');

        $this->returnData($tel);
    }
}