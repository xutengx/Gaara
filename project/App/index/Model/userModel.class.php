<?php
namespace App\index\Model;
defined('IN_SYS')||exit('ACC Denied');
class userModel extends \Main\Core\Model{
    public function newOne($eqw){
        $sql = 'insert into '.$this->table." (`account`)VALUES ('{$eqw}')";
        return $this->db->lastInsertId($sql);
    }
}