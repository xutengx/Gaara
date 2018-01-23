<?php

declare(strict_types = 1);
namespace Gaara\Core\Response\Traits;

use Gaara\Core\Response;

/**
 * 对外链式操作
 */
trait SetTrait {

	/**
	 * 设置Http头信息
	 * @param array $headers
	 * @return Response
	 */
	public function setHeaders(array $headers): Response {
		foreach ($headers as $k => $v)
			$this->setHeader($k . ':' . $v);
		return $this;
	}

	/**
	 * 设置Http头信息
	 * @param string $header
	 * @return Response
	 */
	public function setHeader(string $header): Response {
		header($header);
		return $this;
	}

	/**
	 * 设置Http状态码
	 * @param int $status
	 * @return Response
	 */
	public function setStatus(int $status): Response {
		if (is_null($this->status) && isset(self::$httpStatus[$status])) {
			header('HTTP/1.1 ' . $status . ' ' . self::$httpStatus[$status]);
			// 确保FastCGI模式下正常
			header('Status:' . $status . ' ' . self::$httpStatus[$status]);

			$this->status = $status;
		}
		return $this;
	}

	/**
	 * 设置Http响应的文档类型
	 * @param string $type  eg: json, xml
	 * @return Response
	 */
	public function setContentType(string $type): Response {
		if (is_null($this->contentType) && isset(self::$httpType[$type])) {
			header('Content-Type: ' . reset(self::$httpType[$type]) . '; charset=' . $this->char);
			$this->contentType = $type;
		}
		return $this;
	}

}
