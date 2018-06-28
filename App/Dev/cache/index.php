<?php

namespace App\Dev\cache;

use Gaara\Core\Controller;
use Cache;
use Gaara\Core\Cache\Driver\Redis;
use Gaara\Core\Cache\Driver\File;

class index extends Controller{

    private $num = 3;

    private $fun_array = [
        'Cache->get("不存在的键")' => 'test_1',
        'Cache->set("键","我是值") & Cache->setnx("键","我是值")' => 'test_2',
        'Cache->get("键")' => 'test_3',
        'Cache->rm("键")' => 'test_4',
        'Cache->rm("不存在的键")' => 'test_5',
        'Cache->store(\'file\')->set("store_file_cache","指定使用文件缓存文件缓存(需要手动还原)",100)' => 'test_6',
        'Cache->store()->set("cache","(还原)使用默认的缓存驱动 ",100)' => 'test_7',
        'Cache->remember("手动键", "手动值",100)' => 'test_8',
        'Cache->remember(function(){return "自动键".date("Y-m-d H:i:s");})' => 'test_9',
        '\Cache::call($this, \'tett\', null, 1,2)' => 'test_10',
        '\Cache::ttl("手动键")' => 'test_11',
        '\Cache::increment("自增减", 10)' => 'test_12',
        '\Cache::decrement("自增减", 5)' => 'test_13',
        '\Cache::clear($this, \'tett2\')' => 'test_14',
        '\Cache::dremember()' => 'test_15',
        '\Cache::flunk()' => 'test_16',
        'exit' => 'end',
    ];

    public function indexDo(Cache $Cache) {
        $Cache->store('file');
        $i = 1;
        echo '<pre> ',date("Y-m-d H:i:s"),'<br>';
        foreach ($this->fun_array as $k => $v) {
            echo $i.' . '.$k . ' : <br>';
            run($this, $v);
            echo '<br><hr>';
            $i++;
        }
    }

    private function test_1(Cache $Cache) {

//		$f = new File;
//		$r = $f->testLock('testLock', -5);
//		var_dump($r);exit;


        var_dump($Cache->store('file')->get('不存在的键'));
        var_dump($Cache->store('redis')->get('不存在的键'));
    }

    private function test_2(Cache $Cache) {
        $Cache->set("时间",time());
        var_dump($Cache->set("键","我是值"));
        var_dump($Cache->setnx("键","我是值"));
    }

    private function test_3(Cache $Cache) {
        var_dump($Cache->get("键"));
    }

    private function test_4(Cache $Cache) {
        var_dump($Cache->rm("键"));
    }

    private function test_5(Cache $Cache) {
        var_dump($Cache->rm("不存在的键"));
    }

    private function test_6(Cache $Cache) {
        var_dump($Cache->store('file')->set("store_file_cache","指定使用文件缓存文件缓存(需要手动还原)",100));
        var_dump($Cache->store('file')->ttl("store_file_cache"));
    }

    private function test_7(Cache $Cache) {
        var_dump(\Cache::store()->set("cache","(还原)使用默认的缓存驱动 ",100));
        var_dump($Cache->ttl("cache"));
    }

    private function test_8(Cache $Cache) {
        var_dump(\Cache::store('file')->ttl("手动键"));
        var_dump(\Cache::store('file')->remember("手动键", "手动值 ".date("Y-m-d H:i:s"),100));
        var_dump(\Cache::store('file')->ttl("手动键"));
    }

    private function test_9(Cache $Cache) {
        var_dump(\Cache::remember(function(){return "自动键".date("Y-m-d H:i:s");}));
    }

    private function test_10(Cache $Cache) {
        var_dump(\Cache::store('redis')->call($this, 'tett2', null, 1,2));
    }
    private function tett2(int $num, int $num2){
        return '结果 : '.( $num + $num2 + $this->num ).'  时间 :'.date("Y-m-d H:i:s");
    }

    private function test_11(Cache $Cache) {
        var_dump(\Cache::ttl("手动键"));
    }

    private function test_12(Cache $Cache) {

//		$r = new Redis;
//		var_dump($r->set('t12',2222, 300));
//		var_dump($r->get('t12'));exit;
//		var_dump($r->increment('test1', -4));exit;

        var_dump(Cache::store('file')->increment("自增减1", 10));
        var_dump(Cache::store('redis')->increment("自增减1", 10));
    }

    private function test_13(Cache $Cache) {
        var_dump(Cache::store('file')->decrement("自增减1",5));
        var_dump(Cache::store('redis')->decrement("自增减1",5));
    }

    private function test_14(Cache $Cache) {
        var_dump(\Cache::getDirverName());
        var_dump(\Cache::store('redis')->clear($this, 'tett2'));
    }

    private function test_15(Cache $Cache) {
        var_dump($Cache->dremember(function(){
            return time();
        }));
    }

    private function test_16(Cache $Cache) {
//        var_dump(\Cache::store('file')->flush());
//        var_dump(\Cache::store('redis')->flush());
    }


    private function end() {
        exit();
    }

	public function __destruct() {

        var_export(statistic());
    }
}