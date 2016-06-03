<?php
namespace App\index\Model;
defined('IN_SYS')||exit('ACC Denied');
class userModel extends \Main\Core\Model{
    public function checkUser($account, $passwd, $timelogin){
        $sql = 'select * from '.$this->table.' where `account`="'.$account.'" and `passwd`="'.$passwd.'" and `timeLogin`="'.$timelogin.'"';
        return $this->db->getRow($sql);
    }
    public function userLogin($account, $passwd){
        $sql = 'select id from '.$this->table.' where `account`="'.$account.'" and `passwd`="'.$passwd.'" ';
        return $this->db->getRow($sql);
    }
    public function loginState($id, $timeLogin, $ipLogin){
        $sql = "UPDATE $this->table SET `timeLogin`='$timeLogin',`ipLogin`='$ipLogin' WHERE `id`='$id'";
        return $this->db->execute($sql);
    }
}