<?php
// 开发, 测试, demo 功能3合1
namespace App\development\Contr;
use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');
    
function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}
class indexContr extends Controller\HttpController {
    public function indexDo() {
        $size = 1900000;
        $format = 'Time spent of %s(%d) is : %f seconds.</br>';
        // test of splFixedArray
        $spl_arr = new \splFixedArray($size);
        $start_time = microtime(true);
        for ($i = 0; $i < $size; $i++) {
            $spl_arr[$i] = $i;
        }
        $time_spent = microtime(true) - $start_time;
        printf($format, "splFixedArray", $size, $time_spent);

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
