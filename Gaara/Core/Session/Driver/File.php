<?php

declare(strict_types = 1);
namespace Gaara\Core\Session\Driver;

class File {

	public function __construct($options = array()) {
		$dir = ( isset($options['dir']) && !is_null($options['dir']) ) ? $options['dir']
			: 'data/Session';

		obj('tool')->absoluteDir($dir);

		if (!is_dir($dir))
			obj('tool')->__mkdir($dir);

		ini_set('session.save_handler', 'files');
		ini_set('session.save_path', $dir);
	}

}
