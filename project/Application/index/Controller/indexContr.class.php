<?php
namespace index;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends \Business\businessController{
    private $me = null;
    public function construct(){
        $this->me = obj('userObj')->init(obj('userModule'), 1);
    }
    public function indexDo(){
        $_SESSION['rr'] = 'rrr';
        $this->display('index');
    }
    public function submitData(){
        $data = ($this->post());
        sleep(1);
        $this->returnMsg(1,'w');exit;
        $this->returnData(obj('userModule')->modifyData($this->post()));
    }
}