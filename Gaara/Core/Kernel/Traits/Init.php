<?php

declare(strict_types = 1);
namespace Gaara\Core\Kernel\Traits;

use Gaara\Core\{
	Conf, Pipeline, Kernel, Request, Response
};

trait Init {

	/**
	 * 初始化配置
	 * @return Kernel
	 */
	public function init(): Kernel {
		$this->pipeline	 = $this->make(Pipeline::class);
		$this->request	 = $this->make(Request::class);
		$this->execute($this, 'confInit');
		return $this;
	}

	/**
	 * 初始化配置
	 * @param Conf $conf 配置对象
	 * @return void
	 */
	protected function confInit(Conf $conf) {
		// 配置
		$confApp = $conf->app;

		// 时区
		date_default_timezone_set($confApp['timezone']);

		// php.ini
		$serverIni = $conf->getServerConf('php');
		foreach ($serverIni as $k => $v)
			if (ini_set($k, $v) === false)
				throw new Exception("ini_set($k, $v) is Fail");

		// 报错
		$this->debug = ($confApp['debug'] === '1');

		// 控制台
		$this->cli = (php_sapi_name() === 'cli');
	}

}
