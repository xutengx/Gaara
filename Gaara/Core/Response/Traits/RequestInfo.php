<?php

declare(strict_types = 1);
namespace Gaara\Core\Response\Traits;

trait RequestInfo {

	/**
	 * 获取当前请求的Accept头信息
	 * @return string
	 */
	private function getAcceptType(): string {
		if (isset($_SERVER['HTTP_ACCEPT'])) {
			foreach (self::$httpType as $key => $val) {
				foreach ($val as $v) {
					if (stristr($_SERVER['HTTP_ACCEPT'], $v)) {
						return $key;
					}
				}
			}
		}
		return 'html';
	}

	/**
	 * 获取当前请求的请求方法信息
	 * @return string
	 */
	private function getRequestMethod(): string {
		return \strtolower($_SERVER['REQUEST_METHOD']);
	}

}
