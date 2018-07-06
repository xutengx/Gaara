<?php

declare(strict_types = 1);
namespace Gaara\Core\Response\Traits;

trait GetTrait {

	/**
	 * 得到http描述
	 * @param int $code
	 * @return string
	 */
	public function getMessageByHttpCode(int $code): string {
		return static::$httpStatus[$code];
	}

}
