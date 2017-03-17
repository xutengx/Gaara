<?php
namespace App\admin\Obj;
defined('IN_SYS')||exit('ACC Denied');
class adminObj extends \Main\Core\Object{
    public function construct(){
        $this->init(obj('adminModel'));
    }
    protected function bind() {
        $id = (isset($_SESSION['admin'])&&!empty($_SESSION['admin'])) ? $_SESSION['admin']['id'] : 0;
        $re =  $this->obj_module->check_login_status($_SESSION['admin']['id']);
        if(!empty($re) && $re['passwd'] == $_SESSION['admin']['passwd'] && $re['last_login_time'] == $_SESSION['admin']['last_login_time'] && $re['status'] == $_SESSION['admin']['status']){
            $_SESSION['admin'] = $re;
            return $re;
        }
        else return false;
    }
    protected function bindFalse() {
        headerTo('admin/login/indexDo/');
    }
}