<?php

declare(strict_types = 1);
namespace Gaara\Expand;

use PhpConsole\{Handler, Connector, Storage\File};
use Gaara\Core\{Conf, Tool};
use Gaara\Contracts\ServiceProvider\Single;

/**
 * 借助谷歌浏览器的 php console 插件, 以及 php-console 包, 进行调试
 * @method debug (mixed $info, string $msg);
 */
class PhpConsole implements Single {

	protected $dir = 'phpconsole/';
	protected $path;
	protected $ext = 'log';
	protected $handle;
	protected $password;

	public function __construct(Conf $conf) {
		foreach ($conf->phpconsole as $k => $v) {
			$this->$k = $v;
		}

		Connector::setPostponeStorage(new File($this->makeFilename()));
		$connector = Connector::getInstance();
		if (!is_null($this->password)) {
			$connector->setPassword($this->password);
		}
		$this->handle = Handler::getInstance();
	}

	/**
	 * 返回文件名
	 * @return string
	 * @throws \Gaara\Exception\BindingResolutionException
	 * @throws \ReflectionException
	 */
	protected function makeFilename(): string {
		$this->path = STORAGE . $this->dir;
		$filename   = $this->path . date('Y/m/d') . '.' . $this->ext;
		if (!is_file($filename)) {
			obj(Tool::class)->printInFile($filename, '');
		}
		return $filename;
	}

	public function __call(string $func, array $params) {
		return call_user_func_array([$this->handle, $func], $params);
	}

}
