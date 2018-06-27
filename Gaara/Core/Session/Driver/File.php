<?php

declare(strict_types = 1);
namespace Gaara\Core\Session\Driver;

use Gaara\Core\Tool;

class File {

	/**
	 *
	 * @param string $dir session文件存储位置
	 */
	public function __construct(string $dir) {
		$dir = $dir ?? 'data/Session';

		obj(Tool::class)->absoluteDir($dir);

		if (!is_dir($dir))
			obj(Tool::class)->__mkdir($dir);

		ini_set('session.save_handler', 'files');
		ini_set('session.save_path', $dir);
	}

}
