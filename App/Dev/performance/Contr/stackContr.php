<?php

namespace App\Dev\performance\Contr;

use Gaara\Core\Controller;
use Tool;
/**
 * 栈的实现与比较
 */
class stackContr extends Controller {
    private $type;
    private $popN;
    public function indexDo() {
//        $test_arr = [
//            'http://127.0.0.1/git/php_/project/index.php?path=performance/stack/arrayStack/',
//            'http://127.0.0.1/git/php_/project/index.php?path=performance/stack/redisStack/',
//            'http://127.0.0.1/git/php_/project/index.php?path=performance/stack/splStack/',
//        ];
        $test_arr = [
            'http://127.0.0.1/performance/stack/arrayStack',
            'http://127.0.0.1/performance/stack/redisStack',
            'http://127.0.0.1/performance/stack/splStack',
        ];

        $res = Tool::parallelExe($test_arr);
        var_dump($res);exit;
    }

    public function splStack() {
        $splq = new \SplStack;
        for ($i = 0; $i < 100000; $i++) {
            $data = "hello $i\n";
            $splq->push($data);

            if ($i % 100 == 99 and count($splq) > 100) {
                $popN = rand(10, 99);
                for ($j = 0; $j < $popN; $j++) {
                    $splq->pop();
                }
            }
        }

        $popN = count($splq);
        for ($j = 0; $j < $popN; $j++) {
            $splq->pop();
        }
//        echo '我是spl' . ' 最大长度 ' . $popN;
        $this->popN = $popN;
        $this->type = 'sql';
    }
    
    public function arrayStack() {
        $arrq = array();
        for ($i = 0; $i < 100000; $i++) {
            $data = "hello $i\n";
            $arrq[] = $data;
            if ($i % 100 == 99 and count($arrq) > 100) {
                $popN = rand(10, 99);
                for ($j = 0; $j < $popN; $j++) {
                    array_pop($arrq);
                }
            }
        }
        $popN = count($arrq);
        for ($j = 0; $j < $popN; $j++) {
            array_pop($arrq);
        }
//        echo '我是array' . ' 最大长度 ' . $popN;
        $this->popN = $popN;
        $this->type = 'array';
    }    

    public function redisStack() {
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
        $this->popN = $popN;
        $this->type = 'redis';
        for ($j = 0; $j < $popN; $j++) {
            $redisq->lpop($queue_name);
        }
    }    


    public function __destruct() {
        if (!is_null($this->type)) {
            echo '我是 ' . $this->type . ' 最大长度 ' . $this->popN . '<br>';
            var_dump(\statistic());
            exit;
        }
    }

}
