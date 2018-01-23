<?php

return [
	'default' => env('DB_CONNECTION', '_test'),
	'connections' => [
		'_test'	 => [
			'write'	 => [
				[
					'weight' => 10,
					'type'	 => 'mysql',
					'host'	 => env('DB_HOST'),
					'port'	 => env('DB_PORT'),
					'user'	 => env('DB_USER'),
					'pwd'	 => env('DB_PASSWD'),
					'char'	 => 'UTF8',
					'db'	 => env('DB_DATABASE'),
				]
			],
			'read'	 => [
				[
					'weight' => 1,
					'type'	 => 'mysql',
					'host'	 => env('DB_HOST'),
					'port'	 => env('DB_PORT'),
					'user'	 => env('DB_USER'),
					'pwd'	 => env('DB_PASSWD'),
					'char'	 => 'UTF8',
					'db'	 => env('DB_DATABASE'),
				],
				[
					'weight' => 1,
					'type'	 => 'mysql',
					'host'	 => env('DB_HOST'),
					'port'	 => env('DB_PORT'),
					'user'	 => env('DB_USER'),
					'pwd'	 => env('DB_PASSWD'),
					'char'	 => 'UTF8',
					'db'	 => env('DB_DATABASE'),
				]
			]
		],
		'_best'	 => [
			'write' => [
				[
					'weight' => 10,
					'type'	 => 'mysql',
					'host'	 => '47.90.124.253',
					'port'	 => 21406,
					'user'	 => 'cdr',
					'pwd'	 => env('DB_BEST_PASSWD'),
					'char'	 => 'UTF8',
					'db'	 => 'cdr_report',
				]
			]
		],
		'_dev'	 => [
			'write'	 => [
				[
					'weight' => 10,
					'type'	 => 'mysql',
					'host'	 => '127.0.0.1',
					'port'	 => 3306,
					'user'	 => 'root',
					'pwd'	 => 'root',
					'char'	 => 'UTF8',
					'db'	 => 'test'
				]
			],
			'read'	 => [
				[
					'weight' => 1,
					'type'	 => 'mysql',
					'host'	 => '127.0.0.1',
					'port'	 => 3306,
					'user'	 => 'root',
					'pwd'	 => 'root',
					'char'	 => 'UTF8',
					'db'	 => 'test'
				],
				[
					'weight' => 2,
					'type'	 => 'mysql',
					'host'	 => '127.0.0.1',
					'port'	 => 3306,
					'user'	 => 'root',
					'pwd'	 => 'root',
					'char'	 => 'UTF8',
					'db'	 => 'test'
				]
			]
		]
	]
];
