<?php
namespace Main\Core\Controller\Module;
defined('IN_SYS')||exit('ACC Denied');
trait TransactionModule{
    // 引入sql类
    protected $db = NULL;
    // 事务开启 begin;
    protected function sqlBegin(){
        $this->db = obj('Mysql');
        $sql = 'begin';
        $this->db->query($sql);
    }
    // 事务提交 commit;
    protected function sqlCommit(){
        $sql = 'commit';
        $this->db->query($sql);
    }
    // 事务回滚 rollback
    protected function sqlRollback(){
        $sql = 'rollback';
        $this->db->query($sql);
    }
}