<?php

declare(strict_types = 1);
namespace Gaara\Expand;

use PhpConsole\{
	Handler, Connector, Storage\File
};
use Gaara\Core\{
	Conf, Tool
};
use Gaara\Contracts\ServiceProvider\Single;

/**
 * 借助谷歌浏览器的 php console 插件, 以及 php-console 包, 进行调试
 * @methor debug        (mix, string);
 */
class PhpConsole implements Single {

	private $path	 = 'data/phpconsole/';
	private $ext	 = 'log';
	private $handle;

	public function __construct() {
		$conf		 = obj(Conf::class)->phpconsole;
		Connector::setPostponeStorage(new File($this->makeFilename()));
		$connector	 = Connector::getInstance();
		if (!is_null($conf['passwd'])) {
			$connector->setPassword($conf['passwd']);
		}
		$this->handle = Handler::getInstance();
	}

	/**
	 * 返回文件名
	 * @return string
	 */
	private function makeFilename(): string {
		$filename = ROOT . $this->path . date('Y/m/d') . '.' . $this->ext;
		if (!is_file($filename)) {
			obj(Tool::class)->printInFile($filename, '');
		}
		return $filename;
	}

	public function __call(string $func, array $params) {
		return call_user_func_array([$this->handle, $func], $params);
	}

}
