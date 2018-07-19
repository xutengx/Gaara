<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Gaara\Core\Secure\Traits\{
	Encryption, Atomicity
};
use Gaara\Contracts\ServiceProvider\Single;

class Secure implements Single {

	use Encryption,
	 Atomicity;

	/**
	 * md5
	 * @param string $string
	 * @return string
	 */
	public function md5(string $string): string {
		return md5($string . md5($string));
	}

	/**
	 * 过滤特殊(删除)字符
	 * @param string $string
	 * @param bool $is_strict   严格模式下, 将过滤更多
	 * @return string
	 */
	public function symbol(string $string, bool $is_strict = false): string {
		$risk	 = '~^<>`\'"\\';
		$is_strict and $risk	 .= '@!#$%&?+-*/={}[]()|,.:;';
		$risk	 = str_split($risk, 1);
		return str_replace($risk, '', $string);
	}

}
