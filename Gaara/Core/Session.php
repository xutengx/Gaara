<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Gaara\Core\Session\Driver;

/**
 * Class Session 数据库session存储
 * @package Gaara\Core
 */
class Session {

	// session驱动
	private $Drivers;
	// 是否手动
	private $Manual			 = false;
	// session.name
	private $session_name	 = 'gaara_session';

	final public function __construct($Manual = false) {
		$conf = obj(Conf::class)->session;

		$driver		 = $conf['driver'] ?? 'file';
		$httponly	 = $conf['httponly'] ?? true;
		$lifetime	 = $conf['lifetime'] ?? '600000';
		$autostart	 = $conf['autostart'] ?? true;

		ini_set('session.cookie_httponly', ($httponly === true) ? 'On' : 'Off');
		ini_set('session.cookie_lifetime', (string) $lifetime);
		ini_set('session.gc_maxlifetime', (string) $lifetime);
		ini_set('session.name', $this->session_name);

		switch ($driver) {
			case 'redis':
				$conn = empty($conf['redis']['connection']) ? obj(Conf::class)->redis['default'] : $conf['redis']['connection'];

				$this->Drivers	 = new Driver\Redis($conn);
				break;
			default:
				$this->Drivers	 = new Driver\File($conf['file']['dir']);
				break;
		}


		// 重写后 未完成
		$this->Manual = $Manual;

		if ($autostart)
			session_start();
	}

}
