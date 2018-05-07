<?php

declare(strict_types = 1);
namespace App\Dev\excel;

use Gaara\Core\Request;
use Xutengx\Excel\Excel;

class index extends \Gaara\Core\Controller {

	public function indexDo(Request $request, Excel $Excel) {
		return $Excel->test();
		return 'hello excel';
	}

}
