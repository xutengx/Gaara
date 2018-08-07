<?php

declare(strict_types = 1);
namespace Gaara\Expand;

use Gaara\Expand\IdeHelp\Component\{ClassInfo};

class IdeHelp {

	public function import($class) {

		$classInfo = new ClassInfo($class);

		exit($classInfo->export());
		var_dump($classInfo);
		exit;
	}
}
