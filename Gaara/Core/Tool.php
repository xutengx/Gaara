<?php

declare(strict_types = 1);
namespace Gaara\Core;

use \Gaara\Core\Tool\Traits\{
	RequestTrait, FileTrait, CharacterTrait
};
use Gaara\Contracts\ServiceProvider\Single;

/**
 * 工具类
 */
class Tool implements Single {

// 请求相关
	use RequestTrait;

// 文件操作
	use FileTrait;

// 字符处理
	use CharacterTrait;

	/**
	 * 判断ip是否在某几个范围内
	 * @param string $ip
	 * @param array $Ips 默认规则是内网ip
	 * @return bool
	 */
	public function checkIp(string $ip, array $Ips = null): bool {
		// 内网ip列表
		$ruleIps = $Ips ?? [
			['10.0.0.0', '10.255.255.255'],
			['172.16.0.0', '172.31.255.255'],
			['192.168.0.0', '192.168.255.255'],
			['127.0.0.0', '127.255.255.255']
		];
		$ipInt	 = ip2long(trim($ip));
		foreach ($ruleIps as $rule) {
			if ($ipInt >= ip2long(reset($rule)) && $ipInt <= ip2long(end($rule))) {
				return true;
			}
		}
		return false;
	}

	/**
	 * 生成唯一的 36位 uuid
	 * eg: 54b4c01f-dce0-102a-a4e0-462c07a00c5e
	 * @param string $prefix 盐
	 * @return string
	 */
	public static function uuid(string $prefix = ''): string {
		$chars	 = md5(uniqid($prefix, true));
		$uuid	 = '';
		for ($i = 0; $i < 36; $i++)
			$uuid	 .= ($i === 8 || $i === 15 || $i === 20 || $i === 25) ? '-' : $chars[mt_rand(0, 31)];
		return $uuid;
	}

}
