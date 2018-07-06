<?php

declare(strict_types = 1);
namespace Gaara\Core\Response\Traits;

trait Format {

	/**
	 * 发送一个失败的消息
	 * @param string $msg
	 * @param int $httpCode
	 * @return string
	 */
	public function fail(string $msg = 'Fail', int $httpCode = 400): string {
		return $this->setStatus($httpCode)->returnData(['msg' => $msg]);
	}

	/**
	 * 返回一个正确的消息
	 * @param mixed $data 主要返回内容
	 * @param string $msg 正确消息提示
	 * @param int $httpCode http状态码
	 * @return string
	 */
	public function success($data = [], string $msg = 'Success', int $httpCode = 200): string {
		return $this->setStatus($httpCode)->returnData(['data' => $data, 'msg' => $msg]);
	}

}
