<?php
namespace App\index\Model;
defined('IN_SYS')||exit('ACC Denied');
class messageModel extends \Main\Core\Model{
    // 更改message state状态
    public function checkState($id, $state){
        $sql = "UPDATE $this->table SET `state`='$state' WHERE `id`='$id'";
        return $this->db->execute($sql);
    }
}