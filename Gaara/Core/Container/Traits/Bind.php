<?php

declare(strict_types = 1);
namespace Gaara\Core\Container\Traits;

use Closure;
use Gaara\Core\Container;

trait Bind {

	/**
	 * 手动绑定
	 * @param string $abstract 抽象类/接口/类/自定义的标记
	 * @param Closure|string $concrete 闭包|类名
	 * @param bool $singleton 单例
	 * @param bool $once 一次后销毁
	 * @return Container
	 */
	public function bind(string $abstract, $concrete = null, bool $singleton = false, bool $once = false): Container {
		// 覆盖旧的绑定信息
		$this->dropStaleInstances($abstract);

		// 默认的类实现, 就是其本身
		$concrete = $concrete ?? $abstract;

		// 记录绑定
		$this->bindings[$abstract] = compact('concrete', 'singleton', 'once');

		// 如果是已经绑定的, 将回调存在的监听者
		// todo

		return $this;
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
	 * @param Closure|string $concrete
	 * @param bool $singleton 单例
	 * @return Container
	 */
	public function bindOnce(string $abstract, $concrete = null, bool $singleton = false): Container {
		return $this->bind($abstract, $concrete, $singleton, true);
	}

	/**
	 * 单例绑定
	 * @param string $abstract
	 * @param Closure|string $concrete
	 * @param bool $once 一次后销毁
	 * @return Container
	 */
	public function singleton(string $abstract, $concrete = null, bool $once = false): Container {
		return $this->bind($abstract, $concrete, true, $once);
	}

	/**
	 * Alias a type to a different name.
	 * @param string $abstract
	 * @param string $alias
	 * @return void
	 */
	public function alias($abstract, $alias) {
		$this->aliases[$alias] = $abstract;

		$this->abstractAliases[$abstract][] = $alias;
	}

}
