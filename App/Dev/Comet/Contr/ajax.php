<?php

namespace App\Dev\Comet\Contr;

use Gaara\Core\{
	Controller, Cache, Request
};

class ajax extends Controller {

	protected $view	 = 'App/Dev/Comet/View/';
	protected $title = 'ajax 长轮询 !';

	public function index() {
		$this->assignPhp('title', $this->title);
		return $this->display();
	}

//	public function ajax() {
//		session_write_close();
//		$adminstate	 = $this->cookie('adminstate');
//		$aid		 = $this->cookie('aid');
//		$i			 = 0;
//		do {
//			if (!obj('adminModule')->checkState($aid, $adminstate)) { // 浏览器关闭 or 被T下线
//				$this->ajaxbackWithMsg(0, 'no_login');
//				break;
//			}
//			if ($re = obj('commentsModule', 'index')->getNewState($adminstate)) {
//				$re = $this->makeArr($re);
//				$this->ajaxback($re);
//				break;
//			}
//			if ($i == 25) {
//				$this->ajaxbackWithMsg(2, 'timeout');
//				break;
//			}
//			sleep(1);
//			$i++;
//		} while (1);
//	}
	public function ajaxdo(Cache $Cache) {
//		sleep(2);
//		var_dump($_SESSION); exit;
		return $this->ajaxComet(function() use ($Cache) {
			if ($value = $Cache->rpop('ajax')) {
				return $value;
			}
		}, 1, 30);
	}

	/**
	 * 生成请求签名
	 * @return string
	 * @throws \Exception
	 */
	protected function resolveRequestSignature(): string {
		if (isset($_SESSION)) {
			return session_id() . '|' . static::class;
		}
		throw new \Exception('Need session ');
	}

	protected function ajaxComet(\Closure $callback, int $sleep = 1, int $timeout = 30) {
		$Signature = $this->resolveRequestSignature();
		session_write_close();

		if (obj(Request::class)->input('action')) {
			\Log::info('leave');
			\Cache::set($Signature, 'true');
			// 业务退出标记
			return $this->success([], 'leave');
		}
		$i = 0;
		while ($i++ < $timeout) {

			// 业务退出检测
			if(\Cache::get($Signature)){
				\Cache::rm($Signature);
				return $this->success([], 'stop');
			}

			// 业务调用
			if (!is_null($res = $callback())) {
				return $this->success($res);
			}
			sleep($sleep);
		}
		return $this->fail('timeout', 408);
	}

}
