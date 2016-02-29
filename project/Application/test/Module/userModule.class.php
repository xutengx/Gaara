<?php
namespace test;
defined('IN_SYS')||exit('ACC Denied');
class userModule extends \Main\Module{
    // 核对此openid是否已经记录
    // return int or false
    public function checkUser(array $wechatinfo){
        $sql    = 'select id from '.$this->table.' where openid="'.$wechatinfo['openid'].'"';
        $re     = $this->db->getRow($sql);
        return $re['id'] ? $re['id'] : 0;
    }
    // 建立新的openid记录
    // return int or false
    public function newUser(array $wechatinfo){
        $sql    = 'insert into '.$this->table.' (`name`,img,sex,openid) values ("'.$wechatinfo['nickname'].'","'.$wechatinfo['headimgurl'].'","'.$wechatinfo['sex'].'","'.$wechatinfo['openid'].'")';
        return $this->db->lastInsertId($sql);
    }
    // 查询用户信息
    // return int or false
    public function getUser($id){
        $sql    = 'select * from '.$this->table.' where openid="'.$id.'"';
        return $this->db->getRow($sql);
    }
    // return bool 记录中奖id
    public function winPrize($pid, $userid){
        $sql = 'update '.$this->table.' set prizeid='.$pid.',count=count-1 where id='.$userid;
        return  $this->db->execute($sql);
    }
    // return bool 抽奖次数减1
    public function count($userid){
        $sql = 'update '.$this->table.' set count=count-1 where id='.$userid;
        return  $this->db->execute($sql);
    }
    // 用户信息补全
    public function userInfoAdd($userid, $username, $phone, $email,$adress){
        $sql = 'update '.$this->table.' set phone="'.$phone.'",email="'.$email.'",name="'.$username.'",address="'.$adress.'" where id='.$userid;
        return  $this->db->execute($sql);
    }
}