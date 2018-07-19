<?php

declare(strict_types = 1);
namespace Gaara\Exception;

use Exception;

class ConfException extends Exception {

	public function __construct($message = '', int $code = 500, $previous = null) {
		parent::__construct($message, $code, $previous);
	}

}
