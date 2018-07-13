<?php

declare(strict_types = 1);
//namespace Gaara\Core;



class Container_test {

	// 严格模式, 只注入已经绑定的依赖
	protected $strict = true;


	protected $binds	 = [];
	// 正在绑定的信息
	protected $bindings	 = [];
	protected $instances = [];
	protected $aliases	 = [];
	// 参数流向
	protected $with = [];


	/**
	 * 临时绑定, 同接口实现优先使用一次
	 * @param string $abstract
	 * @param type $concrete
	 */
	public function bindTemporary(string $abstract, $concrete = null, bool $singleton = false){}

	/**
	 * 手动绑定
	 * @param string $abstract 抽象类/接口/类
	 * @param Closure $concrete 实现
	 */
	public function bind(string $abstract, $concrete = null, bool $singleton = false) {
		// 覆盖旧的绑定信息
//		$this->dropStaleInstances($abstract);

		// 默认的类实现, 就是其本身
		$concrete = $concrete ?? $abstract;

		// 将实现转化为闭包, 方便处理
		if (!$concrete instanceof Closure) {
			$concrete = $this->getClosure($abstract, $concrete);
		}

		$this->bindings[$abstract] = compact('concrete', 'singleton');

		// If the abstract type was already resolved in this container we'll fire the
		// rebound listener so that any objects which have already gotten resolved
		// can have their copy of the object updated via the listener callbacks.
//		if ($this->resolved($abstract)) {
//			$this->rebound($abstract);
//		}
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
	 * 为给定的抽象类型激发“回弹”回调。
	 *
	 * @param  string  $abstract
	 * @return void
	 */
	protected function rebound($abstract) {
		$instance = $this->make($abstract);

		foreach ($this->getReboundCallbacks($abstract) as $callback) {
			call_user_func($callback, $this, $instance);
		}
	}

	/**
	 * 单例绑定
	 * @param string $abstract
	 * @param Closure $concrete
	 */
	public function singleton(string $abstract, $concrete) {

	}

	protected function dropStaleInstances(string $abstract) {
		unset($this->instances[$abstract], $this->aliases[$abstract]);
	}

	/**
	 * 转化为闭包
	 * @param string $abstract
	 * @param string $concrete
	 * @return Closure
	 */
	protected function getClosure(string $abstract, string $concrete): Closure {
		return function ($container, $parameters = []) use ($abstract, $concrete) {
			if ($abstract == $concrete) {
				// 实例化
				return $container->build($concrete);
			}
			// 继续解析
			return $container->makeWith($concrete, $parameters);
		};
	}

	/**
	 * 实例化给定类型的具体实例
	 *
	 * @param  string  $concrete
	 * @return mixed
	 *
	 * @throws \Illuminate\Contracts\Container\BindingResolutionException
	 */
	public function build(string $concrete) {
		// If the concrete type is actually a Closure, we will just execute it and
		// hand back the results of the functions, which allows functions to be
		// used as resolvers for more fine-tuned resolution of these objects.
		if ($concrete instanceof Closure) {
			return $concrete($this, $this->getLastParameterOverride());
		}

		$reflector = new ReflectionClass($concrete);

		// If the type is not instantiable, the developer is attempting to resolve
		// an abstract type such as an Interface of Abstract Class and there is
		// no binding registered for the abstractions so we need to bail out.
		if (!$reflector->isInstantiable()) {
			return $this->notInstantiable($concrete);
		}

		$this->buildStack[] = $concrete;

		$constructor = $reflector->getConstructor();

		// If there are no constructors, that means there are no dependencies then
		// we can just resolve the instances of the objects right away, without
		// resolving any other types or dependencies out of these containers.
		if (is_null($constructor)) {
			array_pop($this->buildStack);

			return new $concrete;
		}

		$dependencies = $constructor->getParameters();

		// Once we have all the constructor's parameters we can create each of the
		// dependency instances and then use the reflection instances to make a
		// new instance of this class, injecting the created dependencies in.
		$instances = $this->resolveDependencies(
		$dependencies
		);

		array_pop($this->buildStack);

		return $reflector->newInstanceArgs($instances);
	}

	/**
	 * Resolve the given type from the container.
	 *
	 * @param  string  $abstract
	 * @param  array  $parameters
	 * @return mixed
	 */
	protected function resolve($abstract, $parameters = []) {
		$abstract = $this->getAlias($abstract);

		$needsContextualBuild = !empty($parameters) || !is_null(
		$this->getContextualConcrete($abstract)
		);

		// If an instance of the type is currently being managed as a singleton we'll
		// just return an existing instance instead of instantiating new instances
		// so the developer can keep using the same objects instance every time.
		if (isset($this->instances[$abstract]) && !$needsContextualBuild) {
			return $this->instances[$abstract];
		}

		$this->with[] = $parameters;

		$concrete = $this->getConcrete($abstract);

		// We're ready to instantiate an instance of the concrete type registered for
		// the binding. This will instantiate the types, as well as resolve any of
		// its "nested" dependencies recursively until all have gotten resolved.
		if ($this->isBuildable($concrete, $abstract)) {
			$object = $this->build($concrete);
		} else {
			$object = $this->make($concrete);
		}

		// If we defined any extenders for this type, we'll need to spin through them
		// and apply them to the object being built. This allows for the extension
		// of services, such as changing configuration or decorating the object.
		foreach ($this->getExtenders($abstract) as $extender) {
			$object = $extender($object, $this);
		}

		// If the requested type is registered as a singleton we'll want to cache off
		// the instances in "memory" so we can return it later without creating an
		// entirely new instance of an object on each subsequent request for it.
		if ($this->isShared($abstract) && !$needsContextualBuild) {
			$this->instances[$abstract] = $object;
		}

		$this->fireResolvingCallbacks($abstract, $object);

		// Before returning, we will also set the resolved flag to "true" and pop off
		// the parameter overrides for this build. After those two things are done
		// we will be ready to return back the fully constructed class instance.
		$this->resolved[$abstract] = true;

		array_pop($this->with);

		return $object;
	}

	public function makeWith(string $abstract, array $parameters) {
		return $this->resolve($abstract, $parameters);
	}

	public function make($abstract, $parameters = []) {
		if (isset($this->instances[$abstract])) {
			return $this->instances[$abstract];
		}

		array_unshift($parameters, $this);

		return call_user_func_array($this->bindings[$abstract]['concrete'], $parameters);
	}

}
