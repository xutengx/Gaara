<?php

declare(strict_types = 1);
namespace Gaara\Core\Middleware;

use Gaara\Core\{Middleware, Response};

/**
 * 统一响应处理
 */
class ReturnResponse extends Middleware {

	protected $except = [];

	/**
	 * 兼容不同服务器下, php可能存在的默认缓冲区
	 */
	public function handle() {
		ob_start();
		ob_start();
	}

	/**
	 * 发送响应
	 * @param Response $response
	 */
	public function terminate(Response $response) {
		// 清除非`Response->send()`输出;
		ob_end_clean();
		// 发送响应
		$response->send();
	}

}
