<?php

declare(strict_types = 1);
namespace Gaara\Core\Container\Traits;

use Closure;

trait Bind {

	/**
	 * 手动绑定
	 * @param string $abstract 抽象类/接口/类/自定义的标记
	 * @param Closure|string $concrete
	 * @param $singleton 单例
	 */
	public function bind(string $abstract, $concrete = null, bool $singleton = false) {
		// 覆盖旧的绑定信息
		$this->dropStaleInstances($abstract);
		// 默认的类实现, 就是其本身
		$concrete = $concrete ?? $abstract;
		// 转化为闭包
		if (!$concrete instanceof Closure) {
			$concrete = $this->getClosure($abstract, $concrete);
		}
		// 记录绑定
		$this->bindings[$abstract] = compact('concrete', 'singleton');

		// 如果是已经绑定的, 将回调存在的监听者
		// todo
	}

	/**
	 * 转化为闭包
	 * @param  string  $abstract
	 * @param  string  $concrete
	 * @return Closure
	 */
	protected function getClosure(string $abstract, string $concrete): Closure {
		return function ($container, $parameters = []) use ($abstract, $concrete) {
			if ($abstract === $concrete) {
				return $container->build($concrete);
			}
			return $container->make($concrete, $parameters);
		};
	}

	/**
	 * 移除已经绑定的
	 * @param string $abstract
	 * @return void
	 */
	protected function dropStaleInstances(string $abstract): void {
		unset($this->instances[$abstract], $this->aliases[$abstract]);
	}

	/**
	 * 临时绑定, 同接口实现优先使用一次
	 * @param string $abstract
	 * @param type $concrete
	 */
	public function bindOnce(string $abstract, $concrete = null, bool $singleton = false) {

	}

	/**
	 * 单例绑定
	 * @param string $abstract
	 * @param Closure|string $concrete
	 */
	public function singleton(string $abstract, $concrete = null) {
		return $this->bind($abstract, $concrete, true);
	}

}
