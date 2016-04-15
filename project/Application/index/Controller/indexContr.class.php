<?php
namespace index;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends \Business\businessController{
    private $me = null;
    public function construct(){
        $this->me = obj('userObj')->init(obj('userModule'), 1);
    }
    public function indexDo(){
        $this->display();
    }
    public function submitData(){
        $this->returnData(obj('userModule')->modifyData($this->post()));
    }
}