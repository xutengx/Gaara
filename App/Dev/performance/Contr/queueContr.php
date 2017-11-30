<?php

namespace App\Dev\performance\Contr;

use \Gaara\Core\Controller;
use Tool;
/**
 * 3类队列比较 10万条数据随机入队、出队，使用SplQueue与Array模拟的队列与redisList的比较
 */
class queueContr extends Controller {

    private $type;
    private $popN;
    
    public function indexDo() {
        $test_arr = [
            'http://127.0.0.1/performance/queue/arrayQueue',
            'http://127.0.0.1/performance/queue/redisQueue',
            'http://127.0.0.1/performance/queue/splQueue',
        ];

        $res = obj(Tool::class)->parallelExe($test_arr);
        var_dump($res);exit;
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
//        echo '我是spl' . ' 最大长度 ' . $this->popN = $popN;
        $this->popN = $popN;
        $this->type = 'spl';
        
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
//        echo '我是array' . ' 最大长度 ' . $this->popN = $popN;
        
        $this->popN = $popN;
        $this->type = 'array';
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
        $this->popN = $popN;
        $this->type = 'redis';
        for ($j = 0; $j < $popN; $j++) {
            $redisq->rpop($queue_name);
        }
    }

    public function __destruct() {
        if(!is_null($this->type)){
            echo '我是 '. $this->type . ' 最大长度 ' . $this->popN. '<br>';
            var_dump(\statistic());exit;
        }
    }
}