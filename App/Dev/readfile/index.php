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
	$uuid = $this->uuid();

			$st = str_replace('\'', '\\\'', $v);

			echo "('$uuid','$st'),";
		}

		exit;
	}

	public function uuid() {
		list($usec, $sec) = explode(" ", microtime(false));
		$usec		 = (string) ($usec * 10000000);
		$timestamp	 = \bcadd(bcadd(bcmul($sec, "10000000"), (string) $usec), "621355968000000000");
		$ticks		 = \bcdiv($timestamp, 10000);
		$maxUint	 = 4294967295;
		$high		 = \bcdiv($ticks, $maxUint) + 0;
		$low		 = \bcmod($ticks, $maxUint) - $high;
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

}
