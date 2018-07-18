<?php

declare(strict_types = 1);
namespace Gaara\Core\Container\Traits;

use Closure;
use ReflectionClass;
use ReflectionParameter;
use Gaara\Exception\BindingResolutionException;
use Gaara\Contracts\ServiceProvider\Single;

trait Make {

	/**
	 * 构建对象
	 * @param string $abstract
	 * @param array $parameters
	 * @return mixed
	 */
	public function make(string $abstract, array $parameters = []) {
		return $this->resolve($abstract, $parameters);
	}

	/**
	 * 容器中分析给定的抽象(接口/类)
	 * @param string $abstract
	 * @param array $parameters
	 * @return mixed
	 */
	protected function resolve(string $abstract, array $parameters = []) {
		// 存在接口的实现的结果, 则直接返回
		if (isset($this->instances[$abstract])) {
			return $this->instances[$abstract];
		}

		// 记录参数
		$this->with[] = $parameters;

		// 存在接口的实现
		$concrete = $this->getConcrete($abstract);

		// 声明 $isSingleServiceProvider
		$isSingleServiceProvider = false;

		// 尚不存在, 则建立对象
		$results = $this->build($concrete, $isSingleServiceProvider);

		// 需要缓存的对象 (单例绑定 or 属于SingleServiceProvider), 则缓存
		if ($this->bindings[$abstract]['singleton'] ?? $isSingleServiceProvider) {
			// 缓存抽象的实现
			$this->instances[$abstract] = $results;
			// 如果是对象, 则缓存自己的实现
//			is_object($results) ? $this->instances[get_class($results)] = $results : '';
		}

		// 是否绑定后销毁绑定信息
		if ($this->bindings[$abstract]['once'] ?? false) {
			unset($this->bindings[$abstract]);
		}

		// 移除参数
		array_pop($this->with);

		return $results;
	}

	/**
	 * 优先返回已绑定的抽象
	 * @param string $abstract
	 * @return string|Closure
	 */
	protected function getConcrete(string $abstract) {
		return $this->bindings[$abstract]['concrete'] ?? $abstract;
	}

	/**
	 * 实例化给定抽象的具体实例
	 * @param string|Closure $concrete
	 * @param bool $isSingleServiceProvider
	 * @return mixed
	 */
	public function build($concrete, bool &$isSingleServiceProvider = false) {
		// 是闭包, 则直接执行
		if ($concrete instanceof Closure) {
			return $this->executeClosure($concrete, $this->getLastParameterOverride());
		}

		// 反射
		$reflector = new ReflectionClass($concrete);

		// 不可实例化
		if (!$reflector->isInstantiable()) {
			throw new BindingResolutionException("Target [$concrete] is not instantiable.");
		}

		// 获取类的构造函数
		$constructor = $reflector->getConstructor();

		// 如果没有构造函数, 也就是没有依赖的存在, 则马上返回实例化
		if (is_null($constructor)) {
			array_pop($this->buildStack);
			return new $concrete;
		}
		// 获取类的构造函数的需求参数
		$dependencies = $constructor->getParameters();

		// 解决构造函数的依赖
		$constructorDependentParameters = $this->resolveDependencies($dependencies);

		// 是否属于 SingleServiceProvider
		$isSingleServiceProvider = in_array(Single::class, $reflector->getInterfaceNames(), true);

		// 返回实例化
		return $reflector->newInstanceArgs($constructorDependentParameters);
	}

	/**
	 * 解决依赖
	 * @param array $dependencies 依赖( ReflectionParameter )组成的数组
	 * @return array 构造函数的依赖参数
	 */
	protected function resolveDependencies(array $dependencies): array {
		$results = [];
		foreach ($dependencies as $dependency) {
			// 如果依赖被手动传递, 则立即使用
			if ($this->hasParameterOverride($dependency)) {
				// 获得手动传入的实参
				$results[] = $this->getParameterOverride($dependency);
				continue;
			}
			// 分别解决`基本类型的依赖`与`对象类型依赖`
			$results[] = is_null($dependency->getClass()) ? $this->resolvePrimitive($dependency) : $this->resolveClass($dependency);
		}
		return $results;
	}

	/**
	 * 基本类型的依赖解决
	 * @param ReflectionParameter  $parameter
	 * @return mixed
	 */
	protected function resolvePrimitive(ReflectionParameter $parameter) {
		// 优先使用默认值, 否则给null
		return $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null;
	}

	/**
	 * 对象类型的依赖解决
	 * @param ReflectionParameter  $parameter
	 * @return mixed
	 */
	protected function resolveClass(ReflectionParameter $parameter) {
		try {
			return $this->make($parameter->getClass()->name);
		} catch (BindingResolutionException $e) {
			// 使用默认值
			if ($parameter->isOptional()) {
				return $parameter->getDefaultValue();
			}
			throw $e;
		}
	}

	/**
	 * 是否存在依赖的参数被手动传入
	 * @param ReflectionParameter $dependency
	 * @return bool
	 */
	protected function hasParameterOverride(ReflectionParameter $dependency): bool {
		return array_key_exists($dependency->name, $this->getLastParameterOverride());
	}

	/**
	 * 获得手动传入的且依赖的参数
	 * @param ReflectionParameter $dependency
	 * @return mixed
	 */
	protected function getParameterOverride(ReflectionParameter $dependency) {
		return $this->getLastParameterOverride()[$dependency->name];
	}

	/**
	 * 获得手传入的所有参数
	 * @return array
	 */
	protected function getLastParameterOverride(): array {
		return count($this->with) ? end($this->with) : [];
	}

}
