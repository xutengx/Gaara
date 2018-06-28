<?php

return [
	/*
	  |--------------------------------------------------------------------------
	  | 数据库默认使用的数据库连接
	  |--------------------------------------------------------------------------
	  |
	  |
	 */
	'default_connection' => env('DB_CONNECTION', 'default'),

	/*
	  |--------------------------------------------------------------------------
	  | 数据库可使用的数据库连接
	  |--------------------------------------------------------------------------
	  |
	  |
	 */
	'connections' => [
		'default'	 => [
			'write'	 => [
				[
					'weight' => 10,
					'type'	 => 'mysql',
					'host'	 => env('DB_HOST'),
					'port'	 => env('DB_PORT'),
					'user'	 => env('DB_USER'),
					'pwd'	 => env('DB_PASSWD'),
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
					'db'	 => env('DB_DATABASE'),
				],
				[
					'weight' => 1,
					'type'	 => 'mysql',
					'host'	 => env('DB_HOST'),
					'port'	 => env('DB_PORT'),
					'user'	 => env('DB_USER'),
					'pwd'	 => env('DB_PASSWD'),
					'db'	 => env('DB_DATABASE'),
				]
			]
		],
		'con2'	 => [
			'write' => [
				[
					'weight' => 10,
					'type'	 => 'mysql',
					'host'	 => '47.90.124.253',
					'port'	 => 21406,
					'user'	 => 'cdr',
					'pwd'	 => env('DB_BEST_PASSWD'),
					'db'	 => 'cdr_report',
				]
			]
		],
		'con3'	 => [
			'write'	 => [
				[
					'weight' => 10,
					'type'	 => 'mysql',
					'host'	 => '127.0.0.1',
					'port'	 => 3306,
					'user'	 => 'root',
					'pwd'	 => 'root',
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
					'db'	 => 'test'
				],
				[
					'weight' => 2,
					'type'	 => 'mysql',
					'host'	 => '127.0.0.1',
					'port'	 => 3306,
					'user'	 => 'root',
					'pwd'	 => 'root',
					'db'	 => 'test'
				]
			]
		]
	]
];
