<?php

declare(strict_types = 1);
namespace Gaara\Exception\Http;

use Gaara\Exception\HttpException;

class MethodNotAllowedHttpException extends HttpException {

	public function __construct(string $message = null, int $code = 405, $previous = null) {
		parent::__construct($message, $code, $previous);
	}

}
