<?php

declare(strict_types = 1);
namespace Gaara\Core\Session\Driver;

use Gaara\Core\Conf;

class Redis {

	/**
	 *
	 * @param string $connection redis连接名
	 */
	public function __construct(string $connection) {
		$options = obj(Conf::class)->redis['connections'][$connection];

		$host				 = $options['host'] ?? '127.0.0.1';
		$port				 = $options['port'] ?? 6379;
		$query['auth']		 = $options['password'] ?? '';
		$query['database']	 = $options['database'] ?? 0;

		ini_set('session.save_handler', 'redis');
		ini_set('session.save_path', 'tcp://' . $host . ':' . $port . '?' . http_build_query($query));
	}

}
