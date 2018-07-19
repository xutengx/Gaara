<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Exception;
use Gaara\Contracts\ServiceProvider\Single;
use Gaara\Exception\Conf\{
	UndefinedConnectionException, NotFoundConfFileException, NotFoundServerIniFileException,
	NoConnectionException
};

class Conf implements Single {

	// 配置信息
	protected static $data	 = [];
	// 环境变量
	protected static $env	 = [];

	final public function __construct() {
		$this->setEnv();
	}

	/**
	 * 读取环境变量 env.php 并赋值给 self::$env
	 * 包含多配置的选择
	 * @return void
	 */
	protected function setEnv(): void {
		$data		 = parse_ini_file(ROOT . ".env", true);
		$env		 = $data['ENV'];
		self::$env	 = array_merge($data, $data[$env]);
	}

	/**
	 * 获取环境变量, function env 指向此
	 * @param string $name
	 * @param mixed $default  当此变量不存在时的默认值
	 * @return mixed
	 */
	public function getEnv(string $name, $default = null) {
		return self::$env[$name] ?? $default;
	}

	/**
	 * 服务器相关初始化文件
	 * @param string $filename
	 * @return array
	 * @throws Exception
	 */
	public function getServerConf(string $filename): array {
		$file = CONFIG . 'server/' . $filename . '.php';
		if (is_file($file)) {
			return $this->{'server/' . $filename};
		}
		throw new NotFoundServerIniFileException("[$file]");
	}

	/**
	 * 惰性读取配置文件
	 * @param string $configName
	 * @return mixed
	 */
	public function __get(string $configName) {
		if (array_key_exists($configName, self::$data)) {
			return self::$data[$configName];
		} elseif (is_file($filename = CONFIG . $configName . '.php')) {
			return self::$data[$configName] = require($filename);
		}
		throw new NotFoundConfFileException("[$filename]");
	}

	/**
	 * 得到某个驱动的连接属性
	 * @param string $driver
	 * @param string $connection
	 * @return array
	 * @throws UndefinedConnectionException
	 * @throws NoConnectionException
	 */
	public function getDriverConnection(string $driver, string $connection): array {
		$conf = $this->{$driver};
		if (isset($conf['connections'])) {
			if (isset($conf['connections'][$connection]))
				return $conf['connections'][$connection];
			throw new UndefinedConnectionException("[$driver] didn't have a connection called [$connection].");
		}
		throw new NoConnectionException("[$driver] didn't have connections.");
	}

}
