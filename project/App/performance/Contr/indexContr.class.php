<?php

namespace App\performance\Contr;

use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class indexContr extends Controller\HttpController {

    private $fun_array = [
//        '10万条数据随机入队、出队，使用Spl与Array模拟与redis的比较' => 'test_1',
        '10万条数据随机入栈、出栈，使用Spl与Array模拟与redis的比较' => 'test_2',
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
        obj('queueContr')->indexDo();
    }    
    private function test_2() {
        obj('stackContr')->indexDo();
    }
}
