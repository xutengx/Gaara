<?php

declare(strict_types = 1);
namespace Gaara\Expand\IdeHelp\Component;

use ReflectionProperty;

class PropertyInfo {

	/**
	 * 属性名
	 * @var string
	 */
	public $name;
	/**
	 * 是否为常量
	 * @var bool
	 */
	public $isConst;
	/**
	 * 是否静态
	 * @var bool
	 */
	public $isStatic;
	/**
	 * 可见性 public protected private
	 * @var string
	 */
	public $visibility;
	/**
	 * 是否有默认值
	 * @var bool
	 */
	public $hasDefaultValue;

	/**
	 * @var ReflectionProperty
	 */
	protected $reflector;

	/**
	 * PropertyInfo constructor.
	 * @param ReflectionProperty $property
	 */
	public function __construct(ReflectionProperty $property) {
		$this->reflector       = $property;
		$this->name            = $this->setName();
		$this->isConst         = $this->setIsConst();
		$this->isStatic        = $this->setIsStatic();
		$this->visibility      = $this->setVisibility();
		$this->hasDefaultValue = $this->setHasDefaultValue();

	}

	/**
	 * @return string
	 */
	public function export(): string {
		$static = $this->isStatic ? 'static ' : '';
		return <<<EOF
$this->visibility $static\$$this->name
EOF;

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
	protected function setIsConst(): bool {
		return false;
	}

	/**
	 * @return bool
	 */
	protected function setIsStatic(): bool {
		return $this->reflector->isStatic();
	}

	/**
	 * @return string
	 */
	protected function setVisibility(): string {
		return $this->reflector->isPrivate() ? 'private' : ($this->reflector->isProtected() ? 'protected' : 'public');
	}

	/**
	 * @return bool
	 */
	protected function setHasDefaultValue(): bool {
		return $this->reflector->isDefault();
	}

}
