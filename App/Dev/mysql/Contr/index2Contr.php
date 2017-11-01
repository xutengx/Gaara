<?php

declare(strict_types = 1);
namespace App\Dev\mysql\Contr;

use \Gaara\Core\Controller\HttpController;
use \App\Dev\mysql\Model;

/*
 * 数据库开发测试类
 */

class index2Contr extends HttpController {

    private $fun_array = [
        '多行查询, index自定义索引, 参数为数组形式, 非参数绑定' => 'test_1',
        '单行查询, select参数为string形式, (?)参数绑定' => 'test_2',
        '多条件分组查询, 参数为数组形式, 聚合表达式, 非参数绑定' => 'test_3',
        '简易单行更新, 参数为数组形式, 参数绑定, 返回受影响的行数' => 'test_4',
        '简易单行插入, 参数为数组形式, 参数绑定, 返回bool' => 'test_5',
        '静态调用model, where参数数量为2, 3, where的in条件, where闭包' => 'test_6',
        '静态调用model, where的between条件, having条件, 参数绑定,' => 'test_7',
        '静态调用model, whereRaw(不处理)的and or嵌套条件, 参数绑定,' => 'test_8',
        '闭包事务,' => 'test_9',
        'union,3种链接写法' => 'test_10',
        'where exists,3种链接写法' => 'test_11',
        'model中的pdo使用(原始sql)' => 'test_12',
        'model中的pdo使用 使用pdo的参数绑定, 以不同的参数重复执行同一语句' => 'test_13',
        '链式操作 参数绑定, 以不同的参数重复执行同一语句' => 'test_14',
        '聚合函数' => 'test_15',
        '子查询' => 'test_16',
        '结果分块' => 'test_17',
        'getAll,自定义键名' => 'test_18',
        'MySQL随机获取数据的方法，支持大数据量' => 'test_19',
        '超级' => 'test_20',
        'exit' => 'test_21',
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
        $obj = obj(Model\visitorInfoDev::class);
        $sql = $obj->select(['id', 'name', 'phone'])
            ->where( 'id', '>', '2000')
            ->where('id' ,'<', '2004')
            ->index('id')
            ->order('id','desc')
            ->getAllToSql();
        var_dump($sql);

        $res = $obj->select(['id', 'name', 'phone'])
            ->where( 'id', '>', '2000')
            ->where('id' ,'<', '2004')
            ->index('id')
            ->order('id','desc')
            ->getAll();
        var_dump($res);
    }

    private function test_2() {
        $obj = obj(Model\visitorInfoDev::class);
      
        $sql = $obj->select('id,name,phone')
            ->where( 'scene', '&', '?' )
            ->getRowToSql(['1']);
        var_dump($sql);

        $res = $obj->select('id,name,phone')
            ->where( 'scene', '&', '?' )
            ->getRow(['1']);
        var_dump($res);
    }
    private function test_3() {
        $obj = obj(Model\visitorInfoDev::class);
      
        $sql = $obj->select(['count(id)','sum(id) as sum'])
            ->where('scene' , '&', '1' )
            ->where('name','like', '%t%')
            ->group(['phone'])
            ->getAllToSql();
        var_dump($sql);

        $res = $obj->select(['count(id)','sum(id) as sum'])
            ->where('scene' , '&', '1' )
            ->where('name','like', '%t%')
            ->group(['phone'])
            ->getAll();
        var_dump($res);
    }
    private function test_4() {
        $obj = obj(Model\visitorInfoDev::class);
      
        $sql = $obj
            ->data(['name' => 'autoUpdate'])
            ->dataIncrement('is_del', 1)
            ->where('scene' ,'&', ':scene_1' )
            ->limit(1)
            ->updateToSql([':scene_1' => '1']);
        var_dump($sql);

        $res = $obj
            ->data(['name' => 'autoUpdate'])
            ->dataIncrement('is_del', 1)
            ->where('scene' ,'&', ':scene_1' )
            ->limit(1)
            ->update([':scene_1' => '1']);
        var_dump($res);
    }
    private function test_5() {
        $obj = obj(Model\visitorInfoDev::class);
      
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
        $res = Model\visitorInfoDev::select(['id', 'name', 'phone'])
            ->where( 'scene', '&', ':scene_1')
            ->where( 'phone', '13849494949')
            ->whereIn('id',['100','101','102','103'])
            ->orWhere(function($queryBuiler){
                $queryBuiler->where('id', '102')->where('name','xuteng')
                        ->orWhere(function($re){
                            $re->where('phone','13849494949')
                                    ->whereNotNull('id');
                        });
            })
            ->getAll([':scene_1' => '1']);
        $sql = Model\visitorInfoDev::getLastSql();
        
        var_dump($sql);
        var_dump($res);
    }
    private function test_7() {
        $res = Model\visitorInfoDev::select(['id'])
            ->where( 'scene', '&', ':scene_1')
            ->whereBetween('id', ['100','103' ])
            ->havingIn('id',['100','102'])
            ->group('id')
            ->getAll([':scene_1' => '1']);
        $sql = Model\visitorInfoDev::getLastSql();
        
        var_dump($sql);
        var_dump($res);
    }
    private function test_8() {
        $res = Model\visitorInfoDev::select(['id'])
            ->whereBetween('id','100','103')
            ->whereRaw('id = "106"AND `name` = "xuteng1" OR ( note = "12312312321"AND `name` = "xuteng") OR (id != "103"AND `name` = "xuteng")')
            ->getAll();
        $sql = Model\visitorInfoDev::getLastSql();
        
        var_dump($sql);
        var_dump($res);
    }
    private function test_9(Model\visitorInfoDev $visitorInfo){
        $res = $visitorInfo->transaction(function($obj){
            $obj->data(['name' => ':autoInsertName'])
                ->insert([':autoInsertName' => 'autoInsertName transaction']);
            $obj->data(['name' => ':autoInsertName'])
                ->insert([':autoInsertName' => 'autoInsertName transaction2']);
            $obj->data(['id' => ':autoInsertNam'])
                ->insert([':autoInsertNam' => '12']);
        },3);
        var_dump($res);
    }
    private function test_10(Model\visitorInfoDev $visitorInfo){
        $first = $visitorInfo->select(['id', 'name', 'phone'])
            ->whereBetween('id','100','103');
        
        $res = Model\visitorInfoDev::select(['id', 'name', 'phone'])
            ->whereBetween('id','100','103')
            ->union($first)
            ->union(function($obj){
                $obj->select(['id', 'name', 'phone'])
                ->whereBetween('id','100','103');
            })
            ->unionAll($first->getAllToSql())
            ->getAll();
        $sql = Model\visitorInfoDev::getLastSql();
        
        var_dump($sql);
        var_dump($res);
    }
    private function test_11(Model\visitorInfoDev $visitorInfo){
        $first = $visitorInfo->select(['id', 'name', 'phone'])
            ->whereBetween('id','100','103');
        
        $res = Model\visitorInfoDev::select(['id', 'name', 'phone'])
            ->whereBetween('id','100','103')
            ->whereExists($first)
            ->whereExists(function($obj){
                $obj->select(['id', 'name', 'phone'])
                ->whereBetween('id','100','103');
            })
            ->whereExists($first->getAllToSql())
            ->getAll();
        $sql = Model\visitorInfoDev::getLastSql();
        
        var_dump($sql);
        var_dump($res);
    }
    
    private function test_12(Model\visitorInfoDev $visitorInfo, Model\Best $best){
//        $sql = <<<SQL
//select currency, sum(price) AS sum_price, date_format(create_time, "%Y-%m-%d %H") as "hour", count(*) as count_num from `cdr_file` where (`cdr_file`.`operator_id` in ('1', '2', '3', '4', '5', '19', '21', '31', '32', '6', '8', '15', '16', '24', '22', '25', '26', '27', '28', '29', '35', '33', '34', '36', '38', '40', '7', '10', '11', '13', '12', '14', '18', '23', '37', '39', '17', '20') or `cdr_file`.`operator_id` is null) and (`cdr_file`.`actiontype` in ('1', '2', '4', '5', '6', '7', '8', '9', '10', '11', '13', '14', '16', '17', '18', '12', '15') or `cdr_file`.`actiontype` is null) and (`cdr_file`.`currency` in ('THB', 'VND', 'IDR', 'USD', 'MYR') or `cdr_file`.`currency` is null) and (`cdr_file`.`telco_name` in ('TRUE', 'AIS', 'dtac', 'TrueMoney', 'OneTwoCall', 'Line', 'bank', 'bluewallet', 'BlueCoins', 'Ais', 'True', 'Telkomsel', 'xl', 'indosat', 'unipin', 'smartfren', 'otc', 'indomog', 'hutchison', 'lytoCard', 'atm', 'bank', 'viettel', 'mobifone', 'vinaphone', 'vtc', 'vietnamMobile', 'bank', 'CC', 'UM', 'DG', 'MX', 'Telenor') or `cdr_file`.`telco_name` is null) and `actiontype` in ('2', '4', '5', '8', '11', '13', '14') and `status` = '200' and `create_time` >= '2017-10-25 00:00:00' and `create_time` <= '2017-10-25 23:59:59' group by date_format(create_time, "%Y-%m-%d %H"), `currency`  
//SQL;
//        $PDOStatement = $best->query($sql, 'select');
//        $res = ($PDOStatement->fetchall(\PDO::FETCH_ASSOC));
//        var_dump($sql);
//        var_dump($res);
//        
//        $sql = <<<SQL
//select currency, sum(price) AS sum_price, date_format(create_time, "%Y-%m-%d %H") as "hour", count(*) as count_num from `cdr_file` where (`cdr_file`.`operator_id` in ('1', '2', '3', '4', '5', '19', '21', '31', '32', '6', '8', '15', '16', '24', '22', '25', '26', '27', '28', '29', '35', '33', '34', '36', '38', '40', '7', '10', '11', '13', '12', '14', '18', '23', '37', '39', '17', '20') or `cdr_file`.`operator_id` is null) and (`cdr_file`.`actiontype` in ('1', '2', '4', '5', '6', '7', '8', '9', '10', '11', '13', '14', '16', '17', '18', '12', '15') or `cdr_file`.`actiontype` is null) and (`cdr_file`.`currency` in ('THB', 'VND', 'IDR', 'USD', 'MYR') or `cdr_file`.`currency` is null) and (`cdr_file`.`telco_name` in ('TRUE', 'AIS', 'dtac', 'TrueMoney', 'OneTwoCall', 'Line', 'bank', 'bluewallet', 'BlueCoins', 'Ais', 'True', 'Telkomsel', 'xl', 'indosat', 'unipin', 'smartfren', 'otc', 'indomog', 'hutchison', 'lytoCard', 'atm', 'bank', 'viettel', 'mobifone', 'vinaphone', 'vtc', 'vietnamMobile', 'bank', 'CC', 'UM', 'DG', 'MX', 'Telenor') or `cdr_file`.`telco_name` is null) and `actiontype` in ('2', '4', '5', '8', '11', '13', '14') and `status` = '200' and `create_time` >= '2017-10-24 00:00:00' and `create_time` <= '2017-10-24 23:59:59' group by date_format(create_time, "%Y-%m-%d %H"), `currency`  
//SQL;
//        $PDOStatement = $best->query($sql, 'select');
//        $res = ($PDOStatement->fetchall(\PDO::FETCH_ASSOC));
//        var_dump($sql);
//        var_dump($res);
        
        $sql = 'insert into visitor_info set name="原生sql插入"';
        $PDOStatement = $visitorInfo->query($sql, 'insert');
        $res = ($PDOStatement->rowCount ());
        var_dump($sql);
        var_dump($res);
    }
    
    private function test_13(Model\visitorInfoDev $visitorInfo){
        $sql = 'select * from visitor_info limit :number';
        $PDOStatement = $visitorInfo->prepare($sql);
        
        $PDOStatement->execute([':number' => 1]);
        $res = ($PDOStatement->fetchall(\PDO::FETCH_ASSOC));
        
        $PDOStatement->execute([':number' => 2]);
        $res2 = ($PDOStatement->fetchall(\PDO::FETCH_ASSOC));
        
        $PDOStatement->execute([':number' => 3]);
        $res3 = ($PDOStatement->fetchall(\PDO::FETCH_ASSOC));
        
        var_dump($sql);
        var_dump($res);
        var_dump($res2);
        var_dump($res3);

    }
    
    private function test_14(Model\visitorInfoDev $visitorInfo){
        $p = $visitorInfo->where('id',':id')->selectPrepare();
        
        var_dump($p->getRow([':id' => '12']));
        var_dump($p->getRow([':id' => '11']));
        var_dump($p->getRow([':id' => '102']));
        
        $p2 = $visitorInfo->where('id',':id')->data('name','prepare')->updatePrepare();
        
        var_dump($p2->update([':id' => '12']));
        var_dump($p2->update([':id' => '11']));
        var_dump($p2->update([':id' => '102']));
        
        $p3 = $visitorInfo->data('name',':name')->insertPrepare();
        
        var_dump($p3->insert([':name' => 'prepare']));
        var_dump($p3->insert([':name' => 'prepare']));
        var_dump($p3->insert([':name' => 'prepare']));
        var_dump($p3->insert([':name' => 'prepare']));
        
//        $p4 = $visitorInfo->where('name',':name')->order('id','desc')->limit(1)->deletePrepare();
//        
//        var_dump($p4->delete([':name' => 'prepare']));
//        var_dump($p4->delete([':name' => 'prepare']));
//        var_dump($p4->delete([':name' => 'prepare']));
    }
    
    public function test_15(Model\visitorInfoDev $visitorInfo){
        $res = $visitorInfo->select('name')->where('name',':name')->group('name,note')->count('note', [':name'=>'prepare']);
        var_dump($visitorInfo->getLastSql());
        var_dump($res);
        
        $res = $visitorInfo->where('name',':name')->count('note', [':name'=>'prepare']);
        var_dump($visitorInfo->getLastSql());
        var_dump($res);
        
        $res = $visitorInfo->max('id');
        var_dump($visitorInfo->getLastSql());
        var_dump($res);
        
        $res = $visitorInfo->select('max',function(){
            return 'id';
        })->getRow();
        var_dump($visitorInfo->getLastSql());
        var_dump($res);
        
        $res = $visitorInfo->min('id');
        var_dump($visitorInfo->getLastSql());
        var_dump($res);
        
        $res = $visitorInfo->avg('id');
        var_dump($visitorInfo->getLastSql());
        var_dump($res);
        
        $res = $visitorInfo->sum('id');
        var_dump($visitorInfo->getLastSql());
        var_dump($res);
        
    }
    
    public function test_16(Model\visitorInfoDev $visitorInfo){
        $res = $visitorInfo->whereSubquery('id','in', function($queryBiler){
            $queryBiler->select('id')->whereIn('id',[1991,1992,1993,166]);
        })->sum('id');
        var_dump($visitorInfo->getLastSql());
        var_dump($res);
    }
    
    public function test_17(Model\visitorInfoDev $visitorInfo){
        $res = $visitorInfo->whereIn('id',[1991,1992,1993,3625,3627,166]);

        foreach($res->getChunk() as $k => $v){
            $chunk = $visitorInfo->where('id',$v['id'])->getChunk();
            foreach($chunk as $v2){
                var_dump($v2);
            }
        }
        
        foreach($res->getChunk() as $k => $v){
            var_dump($k);
            var_export($v);
        }
        
    }
    public function test_18(Model\visitorInfoDev $visitorInfo){
        $info = $visitorInfo->whereIn('id',[1991,1992,1993,3625,3627,166])->index(function($row){
            return $row['id'].'--'.$row['name'];
        })->getAll();
        var_dump($info);
    }
    public function test_19(Model\visitorInfoDev $visitorInfo){
        $res = $visitorInfo->inRandomOrder()->limit(5)->getAll();
        var_dump($visitorInfo->getLastSql());
        var_dump($res);
    }
    
    public function test_20(Model\visitorInfoDev $visitorInfo) {
/*
SELECT * FROM `table` WHERE id >= 
 (SELECT floor( RAND() * ((SELECT MAX(id) FROM `table`)-(SELECT MIN(id) FROM `table`)) + (SELECT MIN(id) FROM `table`))) 
    ORDER BY id LIMIT 1;
        */
        $res = $visitorInfo->whereSubQuery('id','>=',function($query){
            $query->noFrom()
//                    ->selectRaw('floor(RAND()*((select max(`id`) from `visitor_info`)-(select min(`id`) from `visitor_info`))+(select min(`id`) from `visitor_info`))')
                    ->select('floor', function($query){
                        $query_b = clone $query;
                        $maxSql = $query->select('max',function(){
                            return 'id';
                        })->sql();
                        $minSql = $query_b->select('min',function(){
                            return 'id';
                        })->sql();
                        return 'rand()*(('.$maxSql.')-('.$minSql.'))+('.$minSql.')';
                    });
            
        })->order('id')->getRow();
        var_dump($visitorInfo->getLastSql());
        var_dump($res);
    }
    
    public function test_21(Model\visitorInfoDev $visitorInfo) {

//        $dir = (new \Gaara\Expand\Image)->newUrl('https://baidu.com');
//        var_dump($dir);
//        $data = 'https://www.baidu.com';
//        echo '<img src="'.(new \chillerlan\QRCode\QRCode($data, new \chillerlan\QRCode\Output\QRImage))->output().'" />';
        exit;
    }
    
    
    
    public function __destruct() {
        
        var_export(statistic());
    }
    
    public function test(Model\visitorInfoDev $visitorInfo){
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
