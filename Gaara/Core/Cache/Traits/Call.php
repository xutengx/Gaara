<?php

declare(strict_types = 1);
namespace Gaara\Core\Cache\Traits;

use ReflectionClass;

trait Call {

	/**
	 * 执行某个方法并缓存, 优先读取缓存 (并非依赖注入)
	 * @param string|object $obj 执行对象
	 * @param string $func 执行方法
	 * @param int $expire 缓存过期时间
	 * @param mixed ...$params 非限定参数
	 * @return mixed
	 */
	public function call($obj, string $func, int $expire = null, ...$params) {
		$key = $this->makeKey($obj, $func, $params);

		return $this->rememberEverythingWithKey(false, $key, function() use ($obj, $func, $params) {
			return $this->runFunc($obj, $func, $params);
		}, $expire);
	}

	/**
	 * 执行某个方法并返回 (并非依赖注入)
	 * @param string|object $obj 执行对象
	 * @param string $func 执行方法
	 * @param int $expire 缓存过期时间
	 * @param mixed ...$params 非限定参数
	 * @return mixed
	 */
	public function dcall($obj, string $func, int $expire = null, ...$params) {
		$key = $this->makeKey($obj, $func, $params);

		return $this->rememberEverythingWithKey(true, $key, function() use ($obj, $func, $params) {
			return $this->runFunc($obj, $func, $params);
		}, $expire);
	}

	/**
	 * 反射执行任意对象的任意方法
	 * @param string|object $obj
	 * @param string $func
	 * @param array $args
	 * @return mixed
	 * @throws \ReflectionException
	 */
	protected function runFunc($obj, string $func, array $args = []) {
		$reflectionClass = new ReflectionClass($obj);
		$method			 = $reflectionClass->getMethod($func);
		$closure		 = $method->getClosure($obj);
		return $closure(...$args);
	}

}
