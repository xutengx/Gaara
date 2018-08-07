<?php

declare(strict_types = 1);
namespace Gaara\Expand\IdeHelp\Component;

use ReflectionClass;

class ClassInfo {

	/**
	 * 类名
	 * @var string
	 */
	public $name;
	/**
	 * 属性信息
	 * @var array
	 */
	public $propertyInfo;
	/**
	 * 方法信息
	 * @var array
	 */
	public $methodInfo;
	/**
	 * 接口信息
	 * @var array
	 */
	public $interfaceArray;

	/**
	 * @var ReflectionClass
	 */
	protected $reflector;

	/**
	 * ClassInfo constructor.
	 * @param string|object $class
	 * @throws \ReflectionException
	 */
	public function __construct($class) {
		$this->reflector      = new ReflectionClass($class);
		$this->name           = $this->setName();
		$this->interfaceArray = $this->setInterfaceArray();
		$this->propertyInfo   = $this->setPropertyInfo();
		$this->methodInfo     = $this->setMethodInfo();
	}

	/**
	 * @return string
	 */
	public function export(): string {
		$property       = $this->exportProperty();
		$method         = $this->exportMethod();
		$interfaceArray = empty($this->interfaceArray) ? '' : ' implements ' . implode(',', $this->interfaceArray);
		return <<<EEE
class $this->name$interfaceArray {
$property
$method
}
EEE;

	}

	/**
	 * @return string
	 */
	protected function setName(): string {
		return $this->reflector->getName();
	}

	/**
	 * @return array
	 */
	protected function setInterfaceArray(): array {
		return $this->reflector->getInterfaceNames();
	}

	/**
	 * @return array
	 */
	protected function setPropertyInfo(): array {
		$propertyInfo = [];
		foreach ($this->reflector->getProperties() as $property)
			$propertyInfo[] = new PropertyInfo($property);
		return $propertyInfo;
	}

	/**
	 * @return array
	 */
	protected function setMethodInfo(): array {
		$methodInfo = [];
		foreach ($this->reflector->getMethods() as $method)
			$methodInfo[] = new MethodInfo($method);
		return $methodInfo;
	}

	/**
	 * @return string
	 */
	protected function exportProperty(): string {
		$code = '';
		foreach ($this->propertyInfo as $property)
			$code .= "\t" . $property->export() . "\n";
		return $code;
	}

	/**
	 * @return string
	 */
	protected function exportMethod(): string {
		$code = '';
		foreach ($this->methodInfo as $method)
			$code .= "\t" . $method->export() . "\n";
		return $code;
	}
}
