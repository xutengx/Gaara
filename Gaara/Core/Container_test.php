<?php

declare(strict_types = 1);

//namespace Gaara\Core;
// https://segmentfault.com/a/1190000008844504


class Container_test {

	// 严格模式, 只注入已经绑定的依赖
	protected $strict		 = true;
	// 正在绑定的信息
	protected $bindings		 = [];
	// 单例对象存储
	protected $instances	 = [];
	protected $aliases		 = [];
	// 依赖参数
	protected $with			 = [];
	// 正在解决的依赖栈
	protected $buildStack	 = [];

	/**
	 * 临时绑定, 同接口实现优先使用一次
	 * @param string $abstract
	 * @param type $concrete
	 */
	public function bindOnce(string $abstract, $concrete = null, bool $singleton = false) {

	}

	/**
	 * 手动绑定
	 * @param string $abstract 抽象类/接口/类/自定义的标记
	 * @param string|null|Closure $concrete 实现
	 * @param $singleton 实现
	 */
	public function bind(string $abstract, string $concrete = null, bool $singleton = false) {
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
	 * @return \Closure
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

	protected function getConcrete($abstract) {
		if (isset($this->bindings[$abstract])) {
			return $this->bindings[$abstract]['concrete'];
		}
		return $abstract;
	}

	/**
	 * 确定给定的抽象类型是否已被解析
	 *
	 * @param  string  $abstract
	 * @return bool
	 */
	public function resolved(string $abstract) {
		if ($this->isAlias($abstract)) {
			$abstract = $this->getAlias($abstract);
		}

		return isset($this->resolved[$abstract]) ||
		isset($this->instances[$abstract]);
	}

	/**
	 * 单例绑定
	 * @param string $abstract
	 * @param Closure $concrete
	 */
	public function singleton(string $abstract, $concrete) {

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
//		if (!is_null($concrete = $this->getContextualConcrete('$' . $parameter->name))) {
//			return $concrete instanceof Closure ? $concrete($this) : $concrete;
//		}
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
