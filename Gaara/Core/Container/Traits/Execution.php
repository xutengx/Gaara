<?php

declare(strict_types = 1);
namespace Gaara\Core\Container\Traits;

use Closure;

trait Execution {

	/**
	 * 执行对象的某个方法, 自动解决依赖
	 * @param string|object $object
	 * @param string $method
	 * @param array $paramter
	 * @return mixed
	 */
	public function run($object, string $method, array $paramter = []){

	}

}
