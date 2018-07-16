<?php

declare(strict_types = 1);
namespace Gaara\Core\Container\Traits;

use Closure;
use ReflectionClass;
use ReflectionParameter;
use Gaara\Core\Request;
use Gaara\Core\Exception\Http\UnprocessableEntityHttpException;

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
	 * Resolve the given type from the container.
	 *
	 * @param  string  $abstract
	 * @param  array  $parameters
	 * @return mixed
	 */
	protected function resolve($abstract, $parameters = []) {
		// 存在接口的实现的结果, 则直接返回
		if (isset($this->instances[$abstract])) {
			return $this->instances[$abstract];
		}
		// 记录参数
		$this->with[] = $parameters;

		// 存在接口的实现
		$concrete = $this->getConcrete($abstract);

		// 尚不存在, 则建立对象
		$obj = $this->build($concrete, $parameters);


		// 需要缓存的对象, 则缓存
		if ($this->bindings[$abstract]['singleton'] ?? false) {
			$this->instances[$abstract] = $obj;
		}

		// 移除参数
		array_pop($this->with);

		return $obj;
	}

	/**
	 *
	 * @param string $abstract
	 * @return string
	 */
	protected function getConcrete(string $abstract) {
		if (isset($this->bindings[$abstract])) {
			return $this->bindings[$abstract]['concrete'];
		}
		return $abstract;
	}

	/**
	 * 实例化给定类型的具体实例
	 * @param  string  $concrete
	 * @return mixed
	 */
	public function build($concrete) {

		// 是闭包, 则直接执行
		if ($concrete instanceof Closure) {
			return $concrete($this, $this->getLastParameterOverride());
		}

		$reflector = new ReflectionClass($concrete);

		if (!$reflector->isInstantiable()) {
			throw new Exception("Target [$concrete] is not instantiable.");
		}

		// 处理栈入栈
		$this->buildStack[] = $concrete;

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

		// 处理栈出栈
		array_pop($this->buildStack);

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
	 * @param  \ReflectionParameter  $parameter
	 * @return mixed
	 */
	protected function resolvePrimitive(ReflectionParameter $parameter) {
		// 使用默认值
		if ($parameter->isDefaultValueAvailable()) {
			return $parameter->getDefaultValue();
		}
		$message = "Unresolvable dependency resolving [\$$parameter->name] in class {$parameter->getDeclaringClass()->getName()}";
		throw new Exception($message);
	}

	/**
	 * 对象类型的依赖解决
	 * @param  \ReflectionParameter  $parameter
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
