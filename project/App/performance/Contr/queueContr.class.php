<?php

namespace App\performance\Contr;

use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

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
            $splq->pop();
        }
        echo '我是splQueue' . ' 最大队列长度 ' . $popN;
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
        echo '我是arrayQueue' . ' 最大队列长度 ' . $popN;
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
                    $redisq->lpop($queue_name);
                }
            }
        }

        $popN = $redisq->lSize($queue_name);
        echo '我是redisQueue' . ' 最大队列长度 ' . $popN;
        for ($j = 0; $j < $popN; $j++) {
            $redisq->lpop($queue_name);
        }
    }

    public function __destruct() {
        \statistic();
    }
}
