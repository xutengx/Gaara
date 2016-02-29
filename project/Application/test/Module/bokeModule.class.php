<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/22 0022
 * Time: 10:09
 */
defined('IN_SYS')||exit('ACC Denied');
class bokeModule extends Module{
    protected $tablename    = 'article';
//    public function getBoke(){
//        //todo
//        return array(
//            array('title'=>'星期天日记', 'content'=>'今天是个好日子!'),
//            array('title'=>'星期2日记', 'content'=>'今天是个特别的好日子!'),
//            array('title'=>'星期1日记', 'content'=>'今天是个苦B的日子!'),
//        );
//    }
    // 获取系统下的最新的3条博客
    public function getBoke(){
        $sql = 'select * from '.$this->table.' order by `time_create` desc limit 3';
        return $this->db->getAll($sql);
    }
}