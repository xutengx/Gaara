<?php

namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class indexContr extends Controller\HttpController {
    private $save_url = 'data/upload/';

    public function construct() {
        $this->save_url = ROOT . $this->save_url;
//        echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/git/lights_app/index.php">检测lights项目</a>';
    }

    public function indexDo() {
        for($i=0; $i<100; $i++){
            $zero = '';
            for($t=0;$t<$i;$t++){
                $zero .= '0';
            }
            $str = '1'.$zero; 
            echo $str.' 对应的十进制  '.bindec($str),'<br>';
        }
        exit;
        $binary_string = '11111111111111111111';
        var_dump(decbin(95595));
        var_dump(bindec($binary_string));exit;
        headerTo('development/index/indexDo/'); // 开发开始
        // 001 010 011 100 101 110 111      // 
        //  1   2    3   4   5   6   7
//        $this->test();
//        obj('categoryModel')->where(['id' => ':id'])->data('test_int=test_int+1')->update([':id' => 3]);
//        obj('categoryModel')->where(['id' => ':id'])->data('test_int=test_int+1')->data(['test_char' => ':ttt'])->update([':id' => 3, ':ttt' => 'rrr']);
//        $this->display();
    }
    
    private function test(){
//        sleep(99);exit('www');
        $url_arr = [
			'http://203.150.54.214/charge/service/productInfoUpdate',
			'http://125.212.202.118:8160/charge/service/productInfoUpdate',
			'http://120.76.101.146:8160/charge/service/productInfoUpdate',
			'http://52.221.94.242:8160/charge/service/productInfoUpdate',
		];
		$r = [];
		$curl_action = function($url){
			$this->writelogAction('请就url: '.$url ."<br>",date('Ymd').'_'.__METHOD__.'_action.log');
			$curl = curl_init();
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_HEADER, 1);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		    curl_setopt($curl, CURLOPT_TIMEOUT_MS,1);//设置超时 1ms
//		    curl_setopt($curl, CURLOPT_TIMEOUT,999);//设置超时 1s
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    $output = curl_exec($curl);
		    curl_close($curl);
		    return $output;
		};
		foreach ($url_arr as $v) {
			$this->writelogAction('每次请求! '.$v ."<br>",date('Ymd').'_'.__METHOD__.'_action.log');
			$r[] = $curl_action($v);
		}
        var_dump($r);
    }
    private function writelogAction($a , $b){
        echo $a;
    }
//    public function __destruct() {
//        \statistic();
//    }
}
