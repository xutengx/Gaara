<?php

namespace App\Dev\performance\Contr;

use \Gaara\Core\Controller;
use Request;
use Tool;

class indexContr extends Controller {

    private $fun_array = [
        '10万条数据随机入队、出队，使用Spl与Array模拟与redis的比较' => 'http://127.0.0.1/performance/index?func=test_1',
        '10万条数据随机入栈、出栈，使用Spl与Array模拟与redis的比较' => 'http://127.0.0.1/performance/index?func=test_2',
        'splFixedArray与phpArray的比较' => 'http://127.0.0.1/performance/index?func=test_3',
    ];

    public function indexDo(Request $request) {
        $func = $request->input('func', 'string');
        if(is_null($func)){
            echo '<pre> ';
            $res = obj(Tool::class)->parallelExe($this->fun_array);
            var_dump($res);exit;
        }else{
            return run($this, $func);         // 依赖注入执行
        }
    }

    private function test_1() {
        obj(queueContr::class)->indexDo();
    }    
    private function test_2() {
        obj(stackContr::class)->indexDo();
    }    
    private function test_3() {
        obj(arrayContr::class)->indexDo();
    }
}
