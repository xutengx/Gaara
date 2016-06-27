<?php
namespace App\index\Model;
defined('IN_SYS')||exit('ACC Denied');
class userModel extends \Main\Core\Model{
    // 电话注册
    public function regByTel($tel, $passwd){
        $data = array(
            'tel'       => ':tel',
            'passwd'    => ':passwd'
        );
        $arg = array(
            ':tel'      => $tel,
            ':passwd'   => $passwd,
        );
        return $this->data($data)->insert($arg);
    }
    // 电话登录
    public function getByTel($tel, $passwd){
        $data = array(
            'tel'       => ':tel',
            'passwd'    => ':passwd'
        );
        $arg = array(
            ':tel'      => $tel,
            ':passwd'   => $passwd,
        );
        return $this->where($data)->getRow($arg);
    }
    // 更改最近登入时间
    public function changeTime(){
        $time = date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']);
        $data = array(
            'time'       => ':time'
        );
        $arg = array(
            ':time'      => $time
        );
        if($this->data($data)->update($arg))
            return $time;
        return false;
    }
}