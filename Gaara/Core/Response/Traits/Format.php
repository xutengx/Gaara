<?php

declare(strict_types = 1);
namespace Gaara\Core\Response\Traits;

use Gaara\Core\Response;

trait Format {

	/**
	 * 发送一个失败的`Response`
	 * @param string $msg
	 * @param int $httpCode
	 * @return Response
	 */
	public function fail(string $msg = 'Fail', int $httpCode = 400): Response {
		return $this->setStatus($httpCode)->setContent(['msg' => $msg]);
	}

	/**
	 * 返回一个正确的`Response`
	 * @param mixed $data 主要返回内容
	 * @param string $msg 正确消息提示
	 * @param int $httpCode http状态码
	 * @return Response
	 */
	public function success($data = [], string $msg = 'Success', int $httpCode = 200): Response {
		return $this->setStatus($httpCode)->setContent(['data' => $data, 'msg' => $msg]);
	}

}
