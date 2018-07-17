<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Closure;
use Gaara\Core\Request;

/**
 * 中间件父类
 */
abstract class Middleware {

	// 路由别名排除
	protected $except = [];

	/**
	 * 按堆执行
	 * @param Closure $next
	 * @return mix
	 */
	final public function implement(Closure $next) {
		// 前置中间件
		$this->doHandle();

		// 传递
		$response = $next();

		// 末置中间件
		$newResponse = $this->doTerminate($response ?? obj(Response::class));

		// 返回
		return $newResponse;
	}

	/**
	 * 执行前置中间件
	 * @return void
	 */
	final protected function doHandle(): void {
		if ($this->effective() && method_exists($this, 'handle')) {
			run($this, 'handle');
		}
	}

	/**
	 * 执行末置中间件, 自动传递`Response`
	 * @param Response $response 上级操作响应结果
	 * @return Response 本次操作的响应
	 */
	final protected function doTerminate(Response $response): Response {
		if ($this->effective() && method_exists($this, 'terminate')) {
			run($this, 'terminate', [$response]);
		}
		return $response;
	}

	/**
	 * 是否执行
	 * @return bool
	 */
	final protected function effective(): bool {
		return !\in_array(obj(Request::class)->alias, $this->except);
	}

}
