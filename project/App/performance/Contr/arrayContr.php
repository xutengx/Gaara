<?php

namespace App\performance\Contr;

use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');
/**
 * php 数组与定长数组
 */
class arrayContr extends Controller\HttpController {

    private $size = 1900000;
    private $format = 'Time spent of %s(%d) is : %f seconds.</br>';
    public function indexDo() {
        $test_arr = [
            'http://127.0.0.1/git/php_/project/index.php?path=performance/array/phpArray/',
            'http://127.0.0.1/git/php_/project/index.php?path=performance/array/splFixedArray/',
        ];

        $res = obj('tool')->parallelExe($test_arr);
        var_dump($res);
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
        printf($format, "splFixedArray", $size, $time_spent);
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
        printf($format, "PHP array", $size, $time_spent);
    }    

    public function __destruct() {
        \statistic();
    }
}
