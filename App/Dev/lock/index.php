<?php

namespace App\Dev\lock;

use Gaara\Core\Controller;
use Cache;
use Gaara\Core\Secure;

class index extends Controller {
/**
 * 同个浏览器将串行, 测试时请使用2个浏览器
 * 最大的可能是客户端，浏览器的限制：根据http 1.1的官方定义， 客户端最好只保持2个连接
A single-user client SHOULD NOT maintain more than 2 connections with any server or proxy.
http://www.w3.org/Protocols/rfc2616/rfc2616-sec8.html#sec8.1.4
 * @return string
 */
	public function bak() {
		$key = 'ttttqq111';
//			return ;
		echo '进入时间' . time();
		$a	 = obj(Secure::class)->lockup($key);
		if ($a) {

			echo '开始执行' . time();
			sleep(5);
			obj(Secure::class)->unlock($key);
			echo '执行结束' . time();
			return 'have done!';
		} else {
			return 'locked !!!!!';
		}
	}

	public function indexDo(){
		$key = 'tt';
		$a = 2;
		$b = 3;
		$answer;
		$锁定成功 = obj(Secure::class)->lock($key, function() use ($a, $b, &$answer){
			sleep(3);
			$answer = $a + $b;
			sleep(2);
//			throw new \Exception('error');
		});
		if($锁定成功){
			return '锁定成功, answer = '.$answer;
		}else{
			return '锁定失败';
		}
	}

}
