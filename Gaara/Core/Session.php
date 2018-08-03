<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Gaara\Contracts\ServiceProvider\Single;
use Gaara\Core\Session\Driver\{File, Redis};

/**
 * session存储
 */
class Session implements Single {

	// session驱动
	protected $driver;
	// 是否手动
	protected $Manual = false;
	// 已经支持的驱动
	protected $supportedDrivers = [
		'redis' => Redis::class,
		'file'  => File::class
	];
	// session.name
	protected $session_name = 'gaara_session';

	final public function __construct($Manual = false) {
		$conf = obj(Conf::class)->session;

		$driver    = $conf['driver'] ?? 'file';
		$httponly  = $conf['httponly'] ?? true;
		$lifetime  = $conf['lifetime'] ?? '600000';
		$autostart = $conf['autostart'] ?? true;

		// 全局初始化
		ini_set('session.cookie_httponly', ($httponly === true) ? 'On' : 'Off');
		ini_set('session.cookie_lifetime', (string)$lifetime);
		ini_set('session.gc_maxlifetime', (string)$lifetime);
		ini_set('session.name', $this->session_name);

		// 初始化对应 driver
		if (array_key_exists($driver, $this->supportedDrivers)) {
			$dependencyArray = $conf[$driver];
			$this->driver    = obj($this->supportedDrivers[$driver], $dependencyArray);
		}
		else
			throw new InvalidArgumentException('Not supported the session driver : ' . $driver . '.');

		// 重写后 未完成
		$this->Manual = $Manual;

		if ($autostart)
			session_start();
	}

}
