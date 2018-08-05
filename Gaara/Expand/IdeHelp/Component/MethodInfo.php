<?php

declare(strict_types = 1);
namespace Gaara\Expand\IdeHelp\Component;

use ReflectionMethod;

class MethodInfo {

	public $name;
	public $isStatic;
	public $isAbstract;
	public $isFinal;
	public $visibility;     // public protected private
	public $hasReturnType;
	public $returnType;

	protected $reflector;
	protected $parameterInfo;

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

	public function export(): string {
		$final      = $this->isFinal ? 'final ' : '';
		$static     = $this->isStatic ? 'static ' : '';
		$parameter  = $this->exportParameter();
		$returnType = $this->hasReturnType ? (': ' . $this->returnType) : '';
		return <<<EOF
$final$this->visibility {$static}function $this->name($parameter)$returnType{}
EOF;

	}

	protected function setName(): string {
		return $this->reflector->getName();
	}

	protected function setIsStatic(): bool {
		return $this->reflector->isStatic();
	}

	protected function setIsAbstract(): bool {
		return $this->reflector->isAbstract();
	}

	protected function setIsFinal(): bool {
		return $this->reflector->isFinal();
	}

	protected function setVisibility(): string {
		return $this->reflector->isPrivate() ? 'private' : ($this->reflector->isProtected() ? 'protected' : 'public');
	}

	protected function setHasReturnType(): bool {
		return $this->reflector->hasReturnType();
	}

	protected function setReturnType() {
		return $this->reflector->getReturnType();
	}

	protected function setParameterInfo(): array {
		$parameterInfo = [];
		foreach ($this->reflector->getParameters() as $parameter)
			$parameterInfo[] = new ParameterInfo($parameter);
		return $parameterInfo;
	}

	protected function exportParameter(): string {
		$code = '';
		foreach ($this->parameterInfo as $parameter)
			$code .= $parameter->export() . ', ';
		return rtrim($code, ', ');
	}
}
