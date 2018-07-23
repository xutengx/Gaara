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

}
