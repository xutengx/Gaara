<?php

declare(strict_types = 1);
namespace Gaara\Expand\IdeHelp\Component;

use ReflectionProperty;

class PropertyInfo {

	public $name;
	public $isConst;
	public $isStatic;
	public $visibility;     // public protected private
	public $hasDefaultValue = false;

	protected $reflector;

	public function __construct(ReflectionProperty $property) {
		$this->reflector       = $property;
		$this->name            = $this->setName();
		$this->isConst         = $this->setIsConst();
		$this->isStatic        = $this->setIsStatic();
		$this->visibility      = $this->setVisibility();
		$this->hasDefaultValue = $this->setHasDefaultValue();

	}

	public function export():string {
		$static = $this->isStatic ? 'static ' : '';
		return <<<EOF
$this->visibility $static\$$this->name
EOF;

	}

	protected function setName(): string {
		return $this->reflector->getName();
	}

	protected function setIsConst(): bool {
		return false;
	}

	protected function setIsStatic(): bool {
		return $this->reflector->isStatic();
	}

	protected function setVisibility(): string {
		return $this->reflector->isPrivate() ? 'private' : ($this->reflector->isProtected() ? 'protected' : 'public');
	}

	protected function setHasDefaultValue():bool{
		return $this->reflector->isDefault();
	}


}
