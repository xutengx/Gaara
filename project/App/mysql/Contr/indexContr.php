<?php

namespace App\mysql\Contr;

use \Main\Core\Controller\HttpController;

use \App\mysql\Model;

defined('IN_SYS') || exit('ACC Denied');
/*
 * 数据库开发测试类
 */

class indexContr extends HttpController {

    private $fun_array = [
        '简易多行查询, 参数为数组形式, 非参数绑定' => 'test_1',
        '简易单行查询, 参数为数组形式, 参数绑定' => 'test_2',
        '多条件分组查询, 参数为数组形式, 非参数绑定' => 'test_3',
        '简易单行更新, 参数为数组形式, 参数绑定, 自动维护时间戳( model自动获取,默认开启 ),返回受影响的行数' => 'test_4',
        '简易单行插入, 参数为数组形式, 参数绑定, 自动维护时间戳( model自动获取,默认开启 ),返回LastInsertID' => 'test_5',
    ];

    public function indexDo() {
        $i = 1;
        echo '<pre> ';
        foreach ($this->fun_array as $k => $v) {
            echo $i.' . '.$k . ' : <br>';
            $this->$v();
            echo '<br><br>';
            $i++;
        }
    }

    private function test_1() {
        
//        define(Model\visitorInfoModel, '123');
        var_dump(new Model\visitorInfoModel);exit;
        $obj = $this->w(Model\visitorInfoModel);exit;
        $obj = obj('visitorInfoModel');
      
        $sql = $obj->select(['id', 'name', 'phone'])
            ->where([ 'id' => [ '>', '101' ]])
            ->where(['id' => ['<', '104']])
            ->getAll(false);
        var_dump($sql);

        $res = $obj->select(['id', 'name', 'phone'])
            ->where([ 'id' => [ '>', '101' ]])
            ->where(['id' => ['<', '104']])
            ->getAll();
        var_dump($res);
    }
    private function w($e){
        var_dump($e);
    }
    private function test_2() {
        $obj = obj('visitorInfoModel');
      
        $sql = $obj->select(['id', 'name', 'phone'])
            ->where([ 'scene' => [ '&', ':scene_1' ]])
            ->getRow(false);
        var_dump($sql);

        $res = $obj->select(['id', 'name', 'phone'])
            ->where([ 'scene' => [ '&', ':scene_1' ]])
            ->getRow([':scene_1' => '1']);
        var_dump($res);
    }
    private function test_3() {
        $obj = obj('visitorInfoModel');
      
        $sql = $obj->select(['id', 'name', 'phone','count(id)','sum(id)'])
            ->where([ 'scene' => [ '&', '1' ]])
            ->where(['name' => ['like', '%t']])
            ->group(['phone'])
            ->getAll(false);
        var_dump($sql);

        $res = $obj->select(['id', 'name', 'phone','count(id)','sum(id)'])
            ->where([ 'scene' => [ '&', ':scene_1' ]])
            ->where(['name' => ['like', '%t']])
            ->group(['phone'])
            ->getAll([':scene_1' => '1']);
        var_dump($res);
    }
    private function test_4() {
        $obj = obj('visitorInfoModel');
      
        $sql = $obj
            ->data(['name' => 'autoUpdate'])
            ->where([ 'scene' => [ '&', ':scene_1' ]])
            ->limit(1)
            ->update(false);
        var_dump($sql);

        $res = $obj
            ->data(['name' => 'autoUpdate'])
            ->where([ 'scene' => [ '&', ':scene_1' ]])
            ->limit(1)
            ->update([':scene_1' => '1']);
        var_dump($res);
    }
    private function test_5() {
        $obj = obj('visitorInfoModel');
      
        $sql = $obj
            ->data(['name' => ':autoUpdate'])
            ->insert(false);
        var_dump($sql);

        $res = $obj
            ->data(['name' => ':autoUpdate'])
            ->insert([':autoUpdate' => 'autoInsertName']);
        var_dump($res);
    }
}
