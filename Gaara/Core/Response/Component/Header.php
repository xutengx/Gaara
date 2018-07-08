<?php

declare(strict_types = 1);
namespace Gaara\Core\Response\Component;

class Header {

	// 未发送的响应头
	private $headers	 = [];
	// 已发送的响应头
	private $sentHeaders = [];

	/**
	 * 查询一个or全部已发送的响应头
	 * @param string $key
	 * @return array
	 */
	public function getSent(string $key = null): array {
		return is_null($key) ? $this->sentHeaders : ($this->sentHeaders[$key] ?? []);
	}

	/**
	 * 查询一个or全部未发送的响应头
	 * @param string $key
	 * @return array
	 */
	public function get(string $key = null): array {
		return is_null($key) ? $this->headers : ($this->headers[$key] ?? []);
	}

	/**
	 * 设置一个响应头, key已存在则覆盖
	 * @param string $key
	 * @param string $value
	 * @return Header
	 */
	public function set(string $key, $value): Header {
		$this->headers[$key] = [$value];
		return $this;
	}

	/**
	 * 添加一个响应头, key已存在也不会覆盖
	 * @param string $key
	 * @param string $value
	 * @return Header
	 */
	public function add(string $key, $value): Header {
		$this->headers[$key][] = $value;
		return $this;
	}

	/**
	 * 移除一个响应头
	 * @param string $key
	 * @return Header
	 */
	public function remove(string $key): Header {
		unset($this->headers[$key]);
		return $this;
	}

	/**
	 * 发送一组or所有响应头
	 * @return Header
	 */
	public function send(string $sendKey = null): Header {
		foreach ($this->headers as $key => $valueArr) {
			if ($sendKey === $key || $sendKey === null) {
				$valueStr = '';
				foreach ($valueArr as $value) {
					$this->sentHeaders[$key][]	 = $value;
					$valueStr					 .= ',' . $value;
				}
				$headerValueStr = ltrim($valueStr, ',');
				if ($key === 'HTTP/1.1') {
					header($key . ' ' . $headerValueStr);
				} else {
					header($key . ':' . $headerValueStr);
				}
				unset($this->headers[$key]);
			}
		}
		return $this;
	}

}
