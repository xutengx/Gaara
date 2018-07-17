<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Gaara\Core\Container\Traits\{
	Bind, Check, Execution, Make
};

class Container {

	use Bind,
	 Check,
	 Execution,
	 Make;

	// 正在绑定的信息
	protected $bindings		 = [];
	// 单例对象存储
	protected $instances	 = [];
	// 别名
	protected $aliases		 = [];
	// 依赖参数
	protected $with			 = [];
	// 正在解决的依赖栈
	protected $buildStack	 = [];

	public function __get(string $name) {
		return $this->$name;
	}

}
