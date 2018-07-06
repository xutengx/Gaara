<?php

declare(strict_types = 1);
namespace Gaara\Core\Exception\Http;

use Gaara\Core\Exception\HttpException;

class UnsupportedMediaTypeHttpException extends HttpException {

	public function __construct(string $message = null, int $code = 415, $previous = null) {
		parent::__construct($message, $code, $previous);
	}

}
