<?php

declare(strict_types = 1);
namespace Gaara\Expand\IdeHelp\Component;

use ReflectionException;
use ReflectionParameter;

class ParameterInfo {

	/**
	 * 参数名
	 * @var string
	 */
	public $name;
	/**
	 * 是否可以为null
	 * @var bool
	 */
	public $allowsNull;
	/**
	 * 是否可以引用传值
	 * @var bool
	 */
	public $isPassedByReference;
	/**
	 * 是否可变 ...$parameter
	 * @var bool
	 */
	public $isVariadic;
	/**
	 * 是否可选
	 * @var bool
	 */
	public $isOptional;
	/**
	 * 是否限制类型
	 * @var bool
	 */
	public $hasType;
	/**
	 * 类型
	 * @var NULL|\ReflectionType
	 */
	public $type;
	/**
	 * 是否存在默认值
	 * @var bool
	 */
	public $isDefaultValueAvailable;
	/**
	 * 是否存在默认值且为常量
	 * @var bool
	 */
	public $isDefaultValueConstant;
	/**
	 * 默认值
	 * @var mixed|null
	 */
	public $defaultValueAvailable;
	/**
	 * 默认常量
	 * @var null|string
	 */
	public $defaultValueConstant;

	/**
	 * @var ReflectionParameter
	 */
	protected $reflector;

	/**
	 * ParameterInfo constructor.
	 * @param ReflectionParameter $parameter
	 */
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

	/**
	 * @return string
	 */
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

	/**
	 * @return string
	 */
	public function code(): string {

	}

	/**
	 * @return string
	 */
	protected function setName(): string {
		return $this->reflector->getName();
	}

	/**
	 * @return bool
	 */
	protected function setAllowsNull(): bool {
		return $this->reflector->allowsNull();
	}

	/**
	 * @return bool
	 */
	protected function setIsPassedByReference(): bool {
		return $this->reflector->isPassedByReference();
	}

	/**
	 * @return bool
	 */
	protected function setIsVariadic(): bool {
		return $this->reflector->isVariadic();
	}

	/**
	 * @return bool
	 */
	protected function setIsOptional(): bool {
		return $this->reflector->isOptional();
	}

	/**
	 * @return bool
	 */
	protected function setHasType(): bool {
		return $this->reflector->hasType();
	}

	/**
	 * @return NULL|\ReflectionType
	 */
	protected function setType() {
		return $this->reflector->getType();
	}

	/**
	 * @return bool
	 */
	protected function setIsDefaultValueAvailable() {
		return $this->reflector->isDefaultValueAvailable();
	}

	/**
	 * @return bool
	 */
	protected function setIsDefaultValueConstant() {
		try {
			return $this->reflector->isDefaultValueConstant();
		} catch (ReflectionException $fd) {
			return false;
		}
	}

	/**
	 * @return mixed|null
	 */
	protected function setDefaultValueAvailable() {
		try {
			return $this->reflector->getDefaultValue();
		} catch (ReflectionException $fd) {
			return null;
		}
	}

	/**
	 * @return null|string
	 */
	protected function setDefaultValueConstant() {
		try {
			return $this->reflector->getDefaultValueConstantName();
		} catch (ReflectionException $fd) {
			return null;
		}
	}
}
