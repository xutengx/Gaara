<?php
namespace Main\Core\Controller\Module;
defined('IN_SYS')||exit('ACC Denied');
trait SessionModule{
    // session 对象
    protected $session = null;
    public function SessionModuleConstruct(){
        $this->session = obj('session');
    }
    protected function session_start(){
        return $this->session->session_start();
    }
    protected function session_commit(){
        return $this->session->session_commit();
    }
    protected function session_write_close(){
        return $this->session->session_write_close();
    }
}