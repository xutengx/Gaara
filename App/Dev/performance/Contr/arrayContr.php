<?php

namespace App\Dev\performance\Contr;

use Tool;
use Main\Core\Controller;
/**
 * php 数组与定长数组
 */
class arrayContr extends Controller\HttpController {

    private $size = 1900000;
    private $format = 'Time spent of %s(%d) is : %f seconds.</br>';
    public function indexDo() {
        $test_arr = [
            'http://gaara.com/performance/array/phpArray',
            'http://gaara.com/performance/array/splFixedArray',
        ];

        $res = obj(Tool::class)->parallelExe($test_arr);
        var_dump($res);exit;
    }

    public function splFixedArray() {
        $size = $this->size;
        $format = $this->format;
         // test of splFixedArray
        $spl_arr = new \splFixedArray($size);
        $start_time = microtime(true);
        for ($i = 0; $i < $size; $i++) {
            $spl_arr[$i] = $i;
        }
        $time_spent = microtime(true) - $start_time;
        \Log::info('1111');
        printf($format, "splFixedArray", $size, $time_spent);exit;
    }
    
    public function phpArray() {
        $size = $this->size;
        $format = $this->format;
         // test of PHP array
        $php_arr = array();
        $start_time = microtime(true);
        for ($i = 0; $i < $size; $i++) {
            $php_arr[$i] = $i;
        }
        $time_spent = microtime(true) - $start_time;
        printf($format, "PHP array", $size, $time_spent);exit;
    }    

    public function __destruct() {
        \statistic();
    }
}
