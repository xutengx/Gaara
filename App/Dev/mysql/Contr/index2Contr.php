<?php

declare(strict_types = 1);
namespace App\Dev\mysql\Contr;

use \Gaara\Core\Controller;
use \App\Dev\mysql\Model;
use Gaara\Core\Exception\MessageException;

/*
 * 数据库开发测试类
 */

class index2Contr extends Controller {

    private $fun_array = [
        '多行查询, index自定义索引, 参数为数组形式, 非参数绑定' => 'test_1',
        '单行查询, select参数为string形式, (?)参数绑定' => 'test_2',
        '多条件分组查询, 参数为数组形式, 聚合表达式, 非参数绑定' => 'test_3',
        '简易单行更新, 参数为数组形式, 参数绑定, 返回受影响的行数' => 'test_4',
        '简易单行插入, 参数为数组形式, 参数绑定, 返回bool' => 'test_5',
        '静态调用model, where参数数量为2, 3, where的in条件, where闭包' => 'test_6',
        '静态调用model, where的between条件, having条件, 参数绑定,' => 'test_7',
        '静态调用model, whereRaw(不处理)的and or嵌套条件, 参数绑定,' => 'test_8',
        '闭包事务, 事物中的锁' => 'test_9',
        'union,3种链接写法' => 'test_10',
        'where exists,3种链接写法' => 'test_11',
        'model中的pdo使用(原始sql), sql 注入测试' => 'test_12',
        'model中的pdo使用 使用pdo的参数绑定, 以不同的参数重复执行同一语句' => 'test_13',
        '链式操作 参数绑定, 以不同的参数重复执行同一语句' => 'test_14',
        '聚合函数' => 'test_15',
        '子查询' => 'test_16',
        '结果分块' => 'test_17',
        'getAll,自定义键名' => 'test_18',
        'MySQL随机获取数据的方法，支持大数据量' => 'test_19',
        '聚合子查询' => 'test_20',
        '自定义的模型连贯操作' => 'test_21',
        '调用mysql中的函数' => 'test_22',
        'where字段之间比较' => 'test_23',
        '测试' => 'test_24',
        'exit' => 'test_99',
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

		$s = $obj->getRow();
		var_dump($s);

		$t = $obj->data(['name' => 'autoInsertName'])->insert();
		var_dump($t);

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
            ->where( 'scene', '&', '1' )
            ->getRowToSql();
        var_dump($sql);

        $res = $obj->select('id,name,phone')
            ->where( 'scene', '&', '1' )
            ->getRow();
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
            ->where('scene' ,'&', '1' )
            ->limit(1)
            ->updateToSql();
        var_dump($sql);

        $res = $obj
            ->data(['name' => 'autoUpdate'])
            ->dataIncrement('is_del', 1)
            ->where('scene' ,'&', '1' )
            ->limit(1)
            ->update();
        var_dump($res);
    }
    private function test_5() {
        $obj = obj(Model\visitorInfoDev::class);

        $sql = $obj
            ->data(['name' => 'autoInsertName'])
            ->insertToSql();
        var_dump($sql);

        $res = $obj
            ->data(['name' => 'autoInsertName'])
            ->insert();
        var_dump($res);
    }
    private function test_6() {
        $res = Model\visitorInfoDev::select(['id', 'name', 'phone'])
            ->where( 'scene', '&', '1')
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
            ->where( 'scene', '&', '1')
            ->whereBetween('id', ['100','103' ])
            ->havingIn('id',['100','102'])
            ->group('id')
            ->getAll();
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
    private function test_9(Model\visitorInfoDev $visitorInfo) {
		$res = $visitorInfo->transaction(function($obj) {
			$tt = $obj->where('id', '>=', "1")->where('id', '<=', "256")->having('id','<>','256')
			->order('id', 'desc')->select('id')->group('id')->lock()->getRow();
			var_dump($tt);

			$obj->data(['name' => 'autoInsertName transaction'])
			->insert();
			$obj->data(['name' => 'autoInsertName transaction2'])
			->insert();
			$obj->data(['id' => '12'])
			->insert();
		}, 3);
		var_dump($res);
	}

	private function test_10(Model\visitorInfoDev $visitorInfo){
        $first = $visitorInfo->select(['id', 'name', 'phone'])
            ->whereBetween('id','100','103');

        $res = Model\visitorInfoDev::select(['id', 'name', 'phone'])
            ->whereBetween('id','100','103')
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
//            ->whereExists($first)
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

    private function test_12(Model\visitorInfoDev $visitorInfo){
		$用户输入	 = 'prepare\' and 0<>(select count(*) from onethink_action) and \'1';
		echo '用户输入:'.$用户输入.'<br><br>' ;

		$da		 = $visitorInfo->where('name', $用户输入)->limit(1)->getAll();
		var_dump($visitorInfo->getLastSql());
		var_dump($da);
		echo '普通使用, sql注入', empty($da) ? '失败, 程序安全 !' : '成功, 程序要凉 !'.'<br><br>' ;

        $sql = <<<SQL
select * from `visitor_info` where `name`='$用户输入' limit 1
SQL;
        $PDOStatement = $visitorInfo->query($sql, 'select');
        $res = ($PDOStatement->fetchall(\PDO::FETCH_ASSOC));
        var_dump($sql);
        var_dump($res);
		echo '原生sql拼接使用, sql注入', empty($da) ? '失败 !' : '成功 !'.'<br><br>' ;

		$da		 = $visitorInfo->where('name', ':name')->limit(1)->getAll([':name'=> $用户输入]);
		var_dump($visitorInfo->getLastSql());
		var_dump($da);
		echo '参数绑定使用, sql注入', empty($da) ? '失败 !' : '成功 !' .'<br><br>' ;

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
        $p = $visitorInfo->where('id',':id')->where('name','xuteng')->selectPrepare();
//		var_dump($p);exit;
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
        $res = $visitorInfo->select('name')->where('name','prepare')->group('name,note')->count('note');
        var_dump($visitorInfo->getLastSql());
        var_dump($res);

        $res = $visitorInfo->where('name','prepare')->count('note');
        var_dump($visitorInfo->getLastSql());
        var_dump($res);

        $res = $visitorInfo->where('name','prepare')->max('id');
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

        foreach($res->getAll() as $k => $v){
            $chunk = $visitorInfo->where('id',$v['id'])->getChunk();
            foreach($chunk as $v2){
                var_dump($v2);
            }
        }
		echo '手动关闭';

		$手动关闭 = $res->getChunk();

        foreach($手动关闭 as $k => $v){
            var_dump($k);
            var_export($v);
			$手动关闭->closeCursor();
			break;
        }

		$res = $visitorInfo->whereIn('id',[1991,1992,1993,3625,3627,166])->getRow();

    }
    public function test_18(Model\visitorInfoDev $visitorInfo){
        $info = $visitorInfo->whereIn('id',[1991,1992,1993,3625,3627,166])->index(function($row){
            return $row['id'].'--'.$row['name'];
        })->limit(2)->getAll();
        var_dump($info);
        $info2 = $visitorInfo->whereIn('id',[1991,1992,1993,3625,3627,166])->index('id')->limit(2)->getAll();
        var_dump($info2);
        $info3 = $visitorInfo->whereIn('id',[1991,1992,1993,3625,3627,166])->limit(2)->getAll();
        var_dump($info3);
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

//        var_dump($visitorInfo->ID_is_bigger_than_1770());exit;

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

        })
                ->ID_rule(2330)
                ->order('id')->getRow();
        var_dump($visitorInfo->getLastSql());
        var_dump($res);
    }

    public function test_22(Model\visitorInfoDev $visitorInfo) {
		$da = $visitorInfo->select('concat_ws', function(){
			return '"-",`name`,`id`';
		})->getRow();
        var_dump($visitorInfo->getLastSql());
		var_dump($da);
    }

    public function test_23(Model\visitorInfoDev $visitorInfo) {
		$da = $visitorInfo->select('concat_ws', function(){
			return '"-",`name`,`id`';
		},'alias')->select(['scene','is_del'])->where('0')->whereColumn('scene','<','is_del')->getAll();
        var_dump($visitorInfo->getLastSql());
		var_dump($da);
    }

    public function test_24(Model\visitorInfoDev $visitorInfo) {
	}

	public function test_99(Model\visitorInfoDev $visitorInfo) {
		ini_set('assertions' , '1');

		assert_options(ASSERT_ACTIVE, 1);
		assert_options(ASSERT_WARNING, 0);
		assert_options(ASSERT_QUIET_EVAL, 1);

//创建处理函数
		$my_assert_handler = function ($file, $line, $code, $desc = null) {
			echo "Assertion failed at $file:$line: $code";
			if ($desc) {
				echo ": $desc";
			}
			echo "\n";
		};

// 设置回调函数
		assert_options(ASSERT_CALLBACK, '$my_assert_handler');

		$a = assert('2 < 1', 'Two is less than one');
		var_dump($a);
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
