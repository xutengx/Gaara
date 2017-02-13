<?php

namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class indexContr extends Controller\HttpController {

    private $save_url = 'data/upload/';

//     // 成功状态统一
//    private $success_status = [
//        '1',1,'success','SUCCESS','true','TRUE',true
//    ];
    public function construct() {
        $this->save_url = ROOT . $this->save_url;
        echo '<a href="http://192.168.142.130/git/lights_app/index.php">检测lights项目</a>';
    }

    public function indexDo() {
//        fclose(fopen('http://127.0.0.1/git/php_/project/index.php?path=index/index/test', 'r'));
        $ch = curl_init();
//        $curl_opt = array(CURLOPT_URL, 'http://127.0.0.1/git/php_/project/index.php?path=index/index/test',
//            CURLOPT_RETURNTRANSFER, 1,
//            CURLOPT_TIMEOUT, 1,);
//        
        //设置抓取的url
        curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1/git/php_/project/index.php?path=index/index/test');
        //设置头文件的信息作为数据流输出  1 要头 0 不要
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1);
//        curl_setopt_array($ch, $curl_opt);
        curl_exec($ch);
        curl_close($ch);
        var_dump($_SERVER);
        var_dump('bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
        exit;
        $test = new staticTest();

        $start = microtime(true);
        for ($i = 0; $i < 10000000; $i++) {
            staticTest::testStatic();
        }
        echo (microtime(true) - $start) . "\n";

        $start = microtime(true);
        for ($i = 0; $i < 10000000; $i++) {
            $test->test();
        }
        echo (microtime(true) - $start) . "\n";

        $start = microtime(true);
        for ($i = 0; $i < 10000000; $i++) {
            $test::testStatic();
        }
        echo (microtime(true) - $start) . "\n";

        $start = microtime(true);
        for ($i = 0; $i < 10000000; $i++) {
            $test->testStatic();
        }
        echo (microtime(true) - $start) . "\n";


//        echo $test->testStatic();
//        echo $test::testStatic();
//        echo staticTest::testStatic();
    }

    public function test() {
        sleep(5);
        obj('Log')->write('yes');
    }

    public function __destruct() {
        \statistic();
    }
}

class staticTest {

    private $par = 'private par';
    private static $static_par = 'static par';

    public function test() {
        $i = 0;
        $i++;
//        return $this->par;
        return self::$static_par;
    }

    public static function testStatic() {
        $i = 0;
        $i++;
        return self::$static_par;
    }
}
