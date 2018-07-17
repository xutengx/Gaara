<?php

declare(strict_types = 1);
namespace Gaara\Core\Middleware;

use Gaara\Core\{
	Middleware, Request, Response
};
use Gaara\Exception\Http\PayloadTooLargeException;

/**
 * 验证 post 数据大小,避免大于php设定的post_max_size
 */
class ValidatePostSize extends Middleware {

	public function handle(Request $request) {
		if ($request->CONTENT_LENGTH > $this->getPostMaxSize()) {
			throw new PayloadTooLargeException;
		}
	}

	/**
	 * Determine the server 'post_max_size' as bytes.
	 * @return int
	 */
	protected function getPostMaxSize(): int {
		if (is_numeric($postMaxSize = ini_get('post_max_size'))) {
			return (int) $postMaxSize;
		}
		$metric = strtoupper(substr($postMaxSize, -1));
		switch ($metric) {
			case 'K':
				return (int) $postMaxSize * 1024;
			case 'M':
				return (int) $postMaxSize * 1048576;
			case 'G':
				return (int) $postMaxSize * 1073741824;
			default:
				return (int) $postMaxSize;
		}
	}

}
