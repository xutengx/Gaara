<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Exception;
use Gaara\Contracts\ServiceProvider\Single;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * 记录日志
 * @method debug        100     (string, array ());
 * @method info         200     (string, array ());
 * @method notice       250     (string, array ());
 * @method warning      300     (string, array ());
 * @method error        400     (string, array ());
 * @method critical     500     (string, array ());
 * @method alert        550     (string, array ());
 * @method emergency    600     (string, array ());
 */
class Log implements Single {

	// 当前环境
	protected $env;
	// 日志路径
	protected $dir = 'log/';
	// 日志绝对路径
	protected $path;
	// 日志文件后缀
	protected $ext = 'log';
	// 通用Logger
	protected $baseHandle;
	// db专用Logger
	protected $databaseHandle;
	// 日志堆
	protected $logStack = [];

	public function __construct(Conf $conf) {
		$this->env = $conf->app['env'];
		foreach ($conf->log as $k => $v) {
			$this->$k = $v;
		}
		$this->setBaseHandle();
		$this->setDatabaseHandle();
	}

	/**
	 * 注册通用Logger
	 * @return void
	 * @throws Exception
	 */
	protected function setBaseHandle(): void {
		$this->baseHandle = new Logger($this->env);
		$formatter        = new LineFormatter(null, null, true, true);
		$this->baseHandle->pushHandler((new StreamHandler($this->makeFilename('debug'), Logger::DEBUG,
			false))->setFormatter($formatter));
		$this->baseHandle->pushHandler((new StreamHandler($this->makeFilename('info'), Logger::INFO,
			false))->setFormatter($formatter));
		$this->baseHandle->pushHandler((new StreamHandler($this->makeFilename('notice'), Logger::NOTICE,
			false))->setFormatter($formatter));
		$this->baseHandle->pushHandler((new StreamHandler($this->makeFilename('warning'), Logger::WARNING,
			false))->setFormatter($formatter));
		$this->baseHandle->pushHandler((new StreamHandler($this->makeFilename('error'), Logger::ERROR,
			false))->setFormatter($formatter));
		$this->baseHandle->pushHandler((new StreamHandler($this->makeFilename('critical'), Logger::CRITICAL,
			false))->setFormatter($formatter));
		$this->baseHandle->pushHandler((new StreamHandler($this->makeFilename('emergency'), Logger::EMERGENCY,
			false))->setFormatter($formatter));
	}

	/**
	 * 返回文件名
	 * @param string $name
	 * @return string
	 */
	protected function makeFilename(string $name): string {
		$this->path = STORAGE . $this->dir;
		$filename   = $this->path . date('Y/m/d/') . $name . '.' . $this->ext;
		return $filename;
	}

	/**
	 * 注册db专用Logger
	 * @return void
	 * @throws Exception
	 */
	protected function setDatabaseHandle(): void {
		$this->databaseHandle = new Logger($this->env);
		$formatter            = new LineFormatter(null, null, true, true);
		$this->databaseHandle->pushHandler((new StreamHandler($this->makeFilename('database'), Logger::DEBUG,
			false))->setFormatter($formatter));
		$this->databaseHandle->pushHandler((new StreamHandler($this->makeFilename('database'), Logger::ERROR,
			false))->setFormatter($formatter));
	}

	/**
	 * 记录 sql 信息
	 * @param string $message
	 * @param array $context
	 * @return bool
	 */
	public function dbInfo(string $message, array $context = []): bool {
		return $this->databaseHandle->addRecord(Logger::INFO, $message, $context);
	}

	/**
	 * 记录 PDOException 抛出的异常中的 sql 信息
	 * @param string $message
	 * @param array $context
	 * @return bool
	 */
	public function dbError(string $message, array $context = []): bool {
		return $this->databaseHandle->addRecord(Logger::ERROR, $message, $context);
	}

	/**
	 * 通用日志记录
	 * @param string $func
	 * @param array $params
	 * @return bool
	 * @throws Exception
	 */
	public function __call(string $func, array $params) {
		if (method_exists($this->baseHandle, $func)) {
			//			$this->logStack[$func][] = $params;
			return call_user_func_array([$this->baseHandle, $func], $params);
		}
		throw new Exception("method [$func] not exist.");
	}

}
