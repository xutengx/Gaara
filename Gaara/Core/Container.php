<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Gaara\Core\Container\Traits\{
	Bind, Make, Execution
};

class Container {

	use Bind,
	 Make,
	 Execution;

	// 严格模式, 只注入已经绑定的依赖
	protected $strict		 = true;
	// 正在绑定的信息
	protected $bindings		 = [];
	// 单例对象存储
	protected $instances	 = [];
	//
//	protected $aliases		 = [];
	// 依赖参数
	protected $with			 = [];
	// 正在解决的依赖栈
	protected $buildStack	 = [];

}
