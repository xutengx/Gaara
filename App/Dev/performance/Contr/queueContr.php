<?php

namespace App\Dev\performance\Contr;

use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');
/**
 * 3类队列比较 10万条数据随机入队、出队，使用SplQueue与Array模拟的队列与redisList的比较
 */
class queueContr extends Controller\HttpController {

    public function indexDo() {
        $test_arr = [
            'http://127.0.0.1/git/php_/project/index.php?path=performance/queue/arrayQueue/',
            'http://127.0.0.1/git/php_/project/index.php?path=performance/queue/redisQueue/',
            'http://127.0.0.1/git/php_/project/index.php?path=performance/queue/splQueue/',
        ];

        $res = obj('tool')->parallelExe($test_arr);
        var_dump($res);
    }

    public function splQueue() {
        $splq = new \SplQueue;
        for ($i = 0; $i < 100000; $i++) {
            $data = "hello $i\n";
            $splq->push($data);

            if ($i % 100 == 99 and count($splq) > 100) {
                $popN = rand(10, 99);
                for ($j = 0; $j < $popN; $j++) {
                    $splq->shift();
                }
            }
        }
        
        $popN = count($splq);
        for ($j = 0; $j < $popN; $j++) {
            $splq->shift();
        }
        echo '我是spl' . ' 最大长度 ' . $popN;
    }

    public function arrayQueue() {
        $arrq = array();
        for ($i = 0; $i < 100000; $i++) {
            $data = "hello $i\n";
            $arrq[] = $data;
            if ($i % 100 == 99 and count($arrq) > 100) {
                $popN = rand(10, 99);
                for ($j = 0; $j < $popN; $j++) {
                    array_shift($arrq);
                }
            }
        }
        
        $popN = count($arrq);
        for ($j = 0; $j < $popN; $j++) {
            array_shift($arrq);
        }
        echo '我是array' . ' 最大长度 ' . $popN;
    }

    public function redisQueue() {
        $queue_name = 'redis_queue';

        $redisq = obj('cache');
        for ($i = 0; $i < 100000; $i++) {
            $data = "hello $i\n";
            $redisq->lpush($queue_name, $data);

            if ($i % 100 == 99 and $redisq->lSize($queue_name) > 100) {
                $popN = rand(10, 99);
                for ($j = 0; $j < $popN; $j++) {
                    $redisq->rpop($queue_name);
                }
            }
        }

        $popN = $redisq->lSize($queue_name);
        echo '我是redis' . ' 最大长度 ' . $popN;
        for ($j = 0; $j < $popN; $j++) {
            $redisq->rpop($queue_name);
        }
    }

    public function __destruct() {
        \statistic();
    }
}
