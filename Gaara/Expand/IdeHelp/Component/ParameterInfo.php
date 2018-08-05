<?php

declare(strict_types = 1);
namespace Gaara\Expand\IdeHelp\Component;

use ReflectionParameter;
use ReflectionException;

class ParameterInfo {

	public $name;
	public $allowsNull;
	public $isPassedByReference; // 是否可以引用传值
	public $isVariadic; // 是否可变
	public $isOptional; // 是否可选
	public $hasType;
	public $type;
	public $isDefaultValueAvailable;
	public $isDefaultValueConstant;
	public $defaultValueAvailable;
	public $defaultValueConstant;

	protected $reflector;

	public function __construct(ReflectionParameter $parameter) {
		$this->reflector               = $parameter;
		$this->name                    = $this->setName();
		$this->allowsNull              = $this->setAllowsNull();
		$this->isPassedByReference     = $this->setIsPassedByReference();
		$this->isVariadic              = $this->setIsVariadic();
		$this->isOptional              = $this->setIsOptional();
		$this->hasType                 = $this->setHasType();
		$this->type                    = $this->setType();
		$this->isDefaultValueAvailable = $this->setIsDefaultValueAvailable();
		$this->isDefaultValueConstant  = $this->setIsDefaultValueConstant();
		$this->defaultValueAvailable   = $this->setDefaultValueAvailable();
		$this->defaultValueConstant    = $this->setDefaultValueConstant();
	}

	public function export(): string {
		$allowsNull         = ($this->allowsNull && $this->hasType &&
		                       ((($this->isDefaultValueConstant || $this->isDefaultValueAvailable) &&
		                         !is_null($this->defaultValueConstant ?? $this->defaultValueAvailable)) ||
		                        (!$this->isDefaultValueConstant && !$this->isDefaultValueAvailable))) ? '?' : '';
		$type               = $this->hasType ? $this->type . ' ' : '';
		$canBePassedByValue = $this->isPassedByReference ? '&' : '';
		$isVariadic         = $this->isVariadic ? '...' : '';
		$showDefaultValue   = ($this->isDefaultValueConstant || $this->isDefaultValueAvailable) ?
			(' = ' . var_export($this->defaultValueConstant ?? $this->defaultValueAvailable, true)) : '';
		return <<<EOF
$allowsNull$type$canBePassedByValue$isVariadic\$$this->name$showDefaultValue
EOF;

	}

	protected function setName(): string {
		return $this->reflector->getName();
	}

	protected function setAllowsNull(): bool {
		return $this->reflector->allowsNull();
	}

	protected function setIsPassedByReference(): bool {
		return $this->reflector->isPassedByReference();
	}

	protected function setIsVariadic(): bool {
		return $this->reflector->isVariadic();
	}

	protected function setIsOptional(): bool {
		return $this->reflector->isOptional();
	}

	protected function setHasType(): bool {
		return $this->reflector->hasType();
	}

	protected function setType() {
		return $this->reflector->getType();
	}

	protected function setIsDefaultValueAvailable() {
		return $this->reflector->isDefaultValueAvailable();
	}

	protected function setIsDefaultValueConstant() {
		try {
			return $this->reflector->isDefaultValueConstant();
		} catch (ReflectionException $fd) {
			return false;
		}
	}

	protected function setDefaultValueAvailable() {
		try {
			return $this->reflector->getDefaultValue();
		} catch (ReflectionException $fd) {
			return null;
		}
	}

	protected function setDefaultValueConstant() {
		try {
			return $this->reflector->getDefaultValueConstantName();
		} catch (ReflectionException $fd) {
			return null;
		}
	}

	public function code(): string {

	}
}
