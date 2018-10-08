<?php

namespace App\Dev\readfile;

use Gaara\Core\Controller;
use Gaara\Core\Request;

class index extends Controller {

	/**
	  insert into sms_template (`template_id`, `content`)values
	  (0,"joker"),
	 * @param Request $request
	 */
	public function indexDo(Request $request) {
		$file = ROOT . 'data/upload/201711/01/insert.txt';

		$string		 = file_get_contents($file);
		$stringArr	 = explode("\r\n", $string);
		echo 'insert into sms_template (`template_id`, `content`) values';
		foreach ($stringArr as $v) {
			$uuid = $this->uuid_st();

			$st = str_replace('\'', '\\\'', $v);

			echo "('$uuid','$st'),";
		}

		exit;
	}

	public function uuid_st() {
		list($usec, $sec) = explode(' ', microtime(false));
		$usec		 = bcmul($usec, '100000000');
		$timestamp	 = bcadd(bcadd(bcmul($sec, '100000000'), $usec), '621355968000000000');
		$ticks		 = bcdiv($timestamp, '10000');
		$maxUint	 = '4294967295';
		$high		 = bcdiv($ticks, $maxUint) + 0;
		$low		 = bcmod($ticks, $maxUint) - $high;
		$highBit	 = (pack("N*", $high));
		$lowBit		 = (pack("N*", $low));
		$guid		 = str_pad(dechex(ord($highBit[2])), 2, "0", STR_PAD_LEFT) . str_pad(dechex(ord($highBit[3])), 2, "0", STR_PAD_LEFT) . str_pad(dechex(ord($lowBit[0])), 2, "0", STR_PAD_LEFT) . str_pad(dechex(ord($lowBit[1])), 2, "0", STR_PAD_LEFT) . "-" . str_pad(dechex(ord($lowBit[2])), 2, "0", STR_PAD_LEFT) . str_pad(dechex(ord($lowBit[3])), 2, "0", STR_PAD_LEFT) . "-";
		$chars		 = "abcdef0123456789";
		for ($i = 0; $i < 4; $i++) {
			$guid .= $chars[mt_rand(0, 15)];
		}
		$guid .= "-";
		for ($i = 0; $i < 4; $i++) {
			$guid .= $chars[mt_rand(0, 15)];
		}
		$guid .= "-";
		for ($i = 0; $i < 12; $i++) {
			$guid .= $chars[mt_rand(0, 15)];
		}

		return $guid;
	}

	public function tttte(string $prefix = ''): string {
		$chars	 = md5(uniqid($prefix, true));
		$uuid	 = '';
		for ($i = 0; $i < 36; $i++)
			$uuid .= ($i === 8 || $i === 15 || $i === 20 || $i === 25) ? '-' : $chars[mt_rand(0, 31)];
		return $uuid;


		for ($i = 0; $i < 8; $i++) {
			$uuid .= $chars[mt_rand(0, 31)];
		}
		$uuid .= '-';
		for ($i = 0; $i < 4; $i++) {
			$uuid .= $chars[mt_rand(0, 31)];
		}
		$uuid .= '-';
		for ($i = 0; $i < 4; $i++) {
			$uuid .= $chars[mt_rand(0, 31)];
		}
		$uuid .= '-';
		for ($i = 0; $i < 12; $i++) {
			$uuid .= $chars[mt_rand(0, 31)];
		}
		return $uuid;
	}

	public function uuid(\Gaara\Core\Tool $t) {
		$ids = [];
		for ($i = 0; $i < 100000; $i++) {
			$ids[] = \Gaara\Core\Tool::uuid();			// 生成 1w个uuid, 用20s; 生成 1k个uuid, 用2s
//			$ids[] = $this->uuid_st();
//			$ids[] = $this->tttte('$prefix');
//			$ids[] = microtime(false);
//			var_dump($this->tttte());exit;

		}
		$arr = array_unique($ids);
//		return $this->success(count($arr));
		return $this->success($ids);
	}

}
