<?php

namespace App\performance\Contr;

use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class indexContr extends Controller\HttpController {

    private $fun_array = [
        '10万条数据随机入队、出队，使用SplQueue与Array模拟的队列的比较' => 'test_1',
    ];

    public function indexDo() {
        $i = 1;
        echo '<pre> ';
        foreach ($this->fun_array as $k => $v) {
            echo $i . ' . ' . $k . ' : <br>';
            $this->$v();
            echo '<br><br>';
            $i++;
        }
    }

    private function test_1() {
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
    }

    private function test_2() {
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
    }

    public function __destruct() {
        \statistic();
    }
}
