<?php

return [
	'driver' => 'redis',
	'expire' => 300,
	'redis'	 => [
		'host'		 => env('REDIS_HOST'),
		'port'		 => env('REDIS_PORT'),
		'passwd'	 => env('REDIS_PASSWD'),
		'database'	 => 0,
	],
	'file'	 => [
		'dir' => 'data/Cache/'
	]
];
