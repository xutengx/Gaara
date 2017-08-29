<?php

namespace App\Dev\mysql\Contr;

use \Main\Core\Controller\HttpController;

use \App\Dev\mysql\Model;

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
        '静态调用model, where参数数量为2, 3, where的in条件, 参数绑定, getLastSql()获取上次执行的sql(不一定是数据库执行的最后条,比如getLastInsertId之类的不会记录)' => 'test_6',
        '静态调用model, where的between条件, 参数绑定,' => 'test_7',
        '静态调用model, where的and or嵌套条件, 参数绑定,' => 'test_8',
        '闭包事务,' => 'test_9',
        '呼叫一个不存在的表,' => 'test_10',
    ];

    public function indexDo() {
        $i = 1;
        echo '<pre> ';
        foreach ($this->fun_array as $k => $v) {
            echo $i.' . '.$k . ' : <br>';
//            $this->$v();          // 执行
            run($this, $v);         // 依赖注入执行
            echo '<br><hr>';
            $i++;
        }
    }
    
    private function test_1() {
        $obj = obj(Model\visitorInfoModel::class);
        
        $sql = $obj->select(['id', 'name', 'phone'])
            ->where([ 'id' => [ '>', '101' ]])
            ->where(['id' => ['<', '104']])
            ->getAllToSql();
        var_dump($sql);

        $res = $obj->select(['id', 'name', 'phone'])
            ->where([ 'id' => [ '>', '101' ]])
            ->where(['id' => ['<', '104']])
            ->getAll();
        var_dump($res);
    }

    private function test_2() {
        $obj = obj(Model\visitorInfoModel::class);
      
        $sql = $obj->select(['id', 'name', 'phone'])
            ->where([ 'scene' => [ '&', ':scene_1' ]])
            ->getRowToSql([':scene_1' => '1']);
        var_dump($sql);

        $res = $obj->select(['id', 'name', 'phone'])
            ->where([ 'scene' => [ '&', ':scene_1' ]])
            ->getRow([':scene_1' => '1']);
        var_dump($res);
    }
    private function test_3() {
        $obj = obj(Model\visitorInfoModel::class);
      
        $sql = $obj->select(['id', 'name', 'phone','count(id)','sum(id)'])
            ->where([ 'scene' => [ '&', '1' ]])
            ->where(['name' => ['like', '%t%']])
            ->group(['phone'])
            ->getAllToSql([':scene_1' => '1']);
        var_dump($sql);

        $res = $obj->select(['id', 'name', 'phone','count(id)','sum(id)'])
            ->where([ 'scene' => [ '&', ':scene_1' ]])
            ->where(['name' => ['like', '%t%']])
            ->group(['phone'])
            ->getAll([':scene_1' => '1']);
        var_dump($res);
    }
    private function test_4() {
        $obj = obj(Model\visitorInfoModel::class);
      
        $sql = $obj
            ->data(['name' => 'autoUpdate'])
            ->where([ 'scene' => [ '&', ':scene_1' ]])
            ->limit(1)
            ->updateToSql([':scene_1' => '1']);
        var_dump($sql);

        $res = $obj
            ->data(['name' => 'autoUpdate'])
            ->where([ 'scene' => [ '&', ':scene_1' ]])
            ->limit(1)
            ->update([':scene_1' => '1']);
        var_dump($res);
    }
    private function test_5() {
        $obj = obj(Model\visitorInfoModel::class);
      
        $sql = $obj
            ->data(['name' => ':autoUpdate'])
            ->insertToSql([':autoUpdate' => 'autoInsertName']);
        var_dump($sql);

        $res = $obj
            ->data(['name' => ':autoUpdate'])
            ->insert([':autoUpdate' => 'autoInsertName']);
        var_dump($res);
    }
    private function test_6() {
        $res = Model\visitorInfoModel::select(['id', 'name', 'phone'])
            ->where( 'scene', '&', ':scene_1')
            ->where( 'phone', '13849494949')
            ->where(['id' => ['in',['100','101','102','103'] ] ])
            ->getAll([':scene_1' => '1']);
        $sql = Model\visitorInfoModel::getLastSql();
        
        var_dump($sql);
        var_dump($res);
    }
    private function test_7() {
        $res = Model\visitorInfoModel::select(['id', 'name', 'phone'])
            ->where( 'scene', '&', ':scene_1')
            ->where(['id' => ['between','100','103' ] ])
            ->getAll([':scene_1' => '1']);
        $sql = Model\visitorInfoModel::getLastSql();
        
        var_dump($sql);
        var_dump($res);
    }
    private function test_8() {
        $res = Model\visitorInfoModel::select(['id', 'name', 'phone'])
            ->where(['id' => ['between','100','103' ] ])
            ->where('id = "106"AND `name` = "xuteng1" OR ( note = "12312312321"AND `name` = "xuteng") OR (id != "103"AND `name` = "xuteng")')
            ->getAll();
        $sql = Model\visitorInfoModel::getLastSql();
        
        var_dump($sql);
        var_dump($res);
    }
    private function test_9(Model\visitorInfoModel $visitorInfo){
        $res = $visitorInfo->transaction(function($obj){
            $obj->data(['name' => ':autoInsertName'])
                ->insert([':autoInsertName' => 'autoInsertName transaction']);
            $obj->data(['name' => ':autoInsertName'])
                ->insert([':autoInsertName' => 'autoInsertName transaction2']);
            $obj->data(['id' => ':autoInsertNam'])
                ->insert([':autoInsertNam' => '432']);
        },3);
        var_dump($res);
    }
    private function test_10(){
        $res = Model\Non::select(['id', 'name', 'phone'])->getAll();
        var_dump($res);
    }
    
    
    
    public function __destruct() {
        \statistic();
    }
    
    public function test(Model\visitorInfoModel $visitorInfo){
        echo '<pre>';
        
        $res = $visitorInfo->transaction(function($obj){

            
            $obj->data(['name' => ':autoInsertName'])
                ->insert([':autoInsertName' => 'autoInsertName transaction']);
            
            
            $obj->data(['name' => ':autoInsertName'])
                ->insert([':autoInsertName' => 'autoInsertName transaction2']);
            $obj->data(['id' => ':autoInsertNam'])
                ->insert([':autoInsertNam' => '432']);
        },3);
        
        var_dump($res);
        exit('ok');
    }
}
