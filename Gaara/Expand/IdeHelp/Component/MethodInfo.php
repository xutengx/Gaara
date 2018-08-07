<?php

declare(strict_types = 1);
namespace Gaara\Expand\IdeHelp\Component;

use ReflectionMethod;

class MethodInfo {

	/**
	 * 方法名
	 * @var string
	 */
	public $name;
	/**
	 * 是否静态方法
	 * @var bool
	 */
	public $isStatic;
	/**
	 * 是否抽象方法
	 * @var bool
	 */
	public $isAbstract;
	/**
	 * 是否最终方法
	 * @var bool
	 */
	public $isFinal;
	/**
	 * 方法可见性 public protected private
	 * @var string
	 */
	public $visibility;
	/**
	 * 是否存在返回值类型声明
	 * @var bool
	 */
	public $hasReturnType;
	/**
	 * 返回值类型声明
	 * @var NULL|\ReflectionType
	 */
	public $returnType;

	/**
	 * 参数信息
	 * @var array
	 */
	protected $parameterInfo;
	/**
	 * @var ReflectionMethod
	 */
	protected $reflector;

	/**
	 * MethodInfo constructor.
	 * @param ReflectionMethod $method
	 */
	public function __construct(ReflectionMethod $method) {
		$this->reflector = $method;

		$this->name          = $this->setName();
		$this->isStatic      = $this->setIsStatic();
		$this->isAbstract    = $this->setIsAbstract();
		$this->isFinal       = $this->setIsFinal();
		$this->visibility    = $this->setVisibility();
		$this->hasReturnType = $this->setHasReturnType();
		$this->returnType    = $this->setReturnType();
		$this->parameterInfo = $this->setParameterInfo();
	}

	/**
	 * @return string
	 */
	public function export(): string {
		$final      = $this->isFinal ? 'final ' : '';
		$static     = $this->isStatic ? 'static ' : '';
		$parameter  = $this->exportParameter();
		$returnType = $this->hasReturnType ? (': ' . $this->returnType) : '';
		return <<<EOF
$final$this->visibility {$static}function $this->name($parameter)$returnType{}
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
	protected function setIsStatic(): bool {
		return $this->reflector->isStatic();
	}

	/**
	 * @return bool
	 */
	protected function setIsAbstract(): bool {
		return $this->reflector->isAbstract();
	}

	/**
	 * @return bool
	 */
	protected function setIsFinal(): bool {
		return $this->reflector->isFinal();
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
	protected function setHasReturnType(): bool {
		return $this->reflector->hasReturnType();
	}

	/**
	 * @return NULL|\ReflectionType
	 */
	protected function setReturnType() {
		return $this->reflector->getReturnType();
	}

	/**
	 * @return array
	 */
	protected function setParameterInfo(): array {
		$parameterInfo = [];
		foreach ($this->reflector->getParameters() as $parameter)
			$parameterInfo[] = new ParameterInfo($parameter);
		return $parameterInfo;
	}

	/**
	 * @return string
	 */
	protected function exportParameter(): string {
		$code = '';
		foreach ($this->parameterInfo as $parameter)
			$code .= $parameter->export() . ', ';
		return rtrim($code, ', ');
	}
}
