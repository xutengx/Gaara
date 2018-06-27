<?php

declare(strict_types = 1);
namespace Gaara\Core\Secure\Traits;

/**
 * 加解密
 */
trait Encryption {

	// 盐
	private $key = 'key';

	/**
	 * 加密
	 * @param string $string
	 * @param string $key
	 * @return string
	 */
	public function encrypt(string $string, string $key = ''): string {
		$key	 = $key ? md5($key) : md5($this->key);
		$j		 = 0;
		$buffer	 = $data	 = '';
		$length	 = strlen($string);
		for ($i = 0; $i < $length; $i++) {
			if ($j === 32) {
				$j = 0;
			}
			$buffer .= $key[$j];
			$j++;
		}
		for ($i = 0; $i < $length; $i++) {
			$data .= $string[$i] ^ $buffer[$i];
		}
		return $this->base64_encode($data);
	}

	/**
	 * 解密
	 * @param string $string
	 * @param string $key
	 * @return string
	 */
	public function decrypt(string $string, string $key = ''): string {
		$key	 = $key ? md5($key) : md5($this->key);
		$string	 = $this->base64_decode($string);
		$j		 = 0;
		$buffer	 = $data	 = '';
		$length	 = strlen($string);
		for ($i = 0; $i < $length; $i++) {
			if ($j === 32) {
				$j = 0;
			}
			$buffer .= substr($key, $j, 1);
			$j++;
		}
		for ($i = 0; $i < $length; $i++) {
			$data .= $string[$i] ^ $buffer[$i];
		}
		return $data;
	}

	/**
	 * URL安全的字符串编码
	 * @param string $string
	 * @return string
	 */
	public function base64_encode(string $string): string {
		$data	 = base64_encode($string);
		$data	 = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
		return $data;
	}

	/**
	 * URL安全的字符串编码的解码
	 * @param string $string
	 * @return string
	 */
	public function base64_decode(string $string): string {
		$data	 = str_replace(array('-', '_'), array('+', '/'), $string);
		$mod4	 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}

}
