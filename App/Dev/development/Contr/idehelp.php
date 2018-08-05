<?php

// 开发, 测试, demo 功能3合1
namespace App\Dev\development\Contr;

use Gaara\Core\Controller;
use Gaara\Core\Cache;
use ReflectionClass;
use ReflectionMethod;
use Gaara\Expand\IdeHelp as a;

class idehelp extends Controller {

	/**
	 * @var array
	 */
	protected $classList = [
		Cache::class
	];

	public function index(a $ideHelp) {
		foreach ($this->classList as $class){
			$ideHelp->import($class);
		}

	}


}
