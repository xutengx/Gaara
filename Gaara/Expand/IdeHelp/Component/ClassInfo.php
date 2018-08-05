<?php

declare(strict_types = 1);
namespace Gaara\Expand\IdeHelp\Component;

use ReflectionClass;

class ClassInfo {

	public $name;
	public $propertyInfo;
	public $methodInfo;
	public $interfaceArray;

	protected $reflector;

	public function __construct($class) {
		$this->reflector      = new ReflectionClass($class);
		$this->name           = $this->setName();
		$this->interfaceArray = $this->setInterfaceArray();
		$this->propertyInfo   = $this->setPropertyInfo();
		$this->methodInfo     = $this->setMethodInfo();
	}

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

	protected function setName(): string {
		return $this->reflector->getName();
	}

	protected function setInterfaceArray(): array {
		return $this->reflector->getInterfaceNames();
	}

	protected function setPropertyInfo(): array {
		$propertyInfo = [];
		foreach ($this->reflector->getProperties() as $property)
			$propertyInfo[] = new PropertyInfo($property);
		return $propertyInfo;
	}

	protected function setMethodInfo(): array {
		$methodInfo = [];
		foreach ($this->reflector->getMethods() as $method)
			$methodInfo[] = new MethodInfo($method);
		return $methodInfo;
	}

	protected function exportProperty(): string {
		$code = '';
		foreach ($this->propertyInfo as $property)
			$code .= "\t" . $property->export() . "\n";
		return $code;
	}

	protected function exportMethod(): string {
		$code = '';
		foreach ($this->methodInfo as $method)
			$code .= "\t" . $method->export() . "\n";
		return $code;
	}
}
