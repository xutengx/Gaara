<?php

declare(strict_types = 1);
namespace Gaara\Exception\Http;

use Gaara\Exception\HttpException;

class ForbiddenHttpException extends HttpException {

	public function __construct(string $message = null, int $code = 403, $previous = null) {
		parent::__construct($message, $code, $previous);
	}

}
