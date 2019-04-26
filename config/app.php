<?php

return [
	/*
	  |--------------------------------------------------------------------------
	  | 当前环境
	  |--------------------------------------------------------------------------
	 */
	'env' => env('ENV', 'local'),
	/*
	  |--------------------------------------------------------------------------
	  | 时区
	  |--------------------------------------------------------------------------
	 */
	'timezone' => 'PRC',
	/*
	  |--------------------------------------------------------------------------
	  | 字符编码
	  |--------------------------------------------------------------------------
	 */
	'char' => 'utf-8',
	/*
	  |--------------------------------------------------------------------------
	  | 开启报错
	  |--------------------------------------------------------------------------
	 */
	'debug' => env('DEBUG'),
	/*
	  |--------------------------------------------------------------------------
	  | 可写文件存放路径
	  |--------------------------------------------------------------------------
	  |
	  | eg:/mnt/hgfs/www/git/php_/project/storage/
	  |
	 */
	'storage' => ROOT . 'storage/',
	/*
	  |--------------------------------------------------------------------------
	  | 服务提供
	  |--------------------------------------------------------------------------
	 */
	'providers' => [
		Gaara\Core\ServiceProvider\Cache::class,
		Gaara\Core\ServiceProvider\Request::class,
		Gaara\Core\ServiceProvider\Route::class,
		Gaara\Core\ServiceProvider\Pipeline::class,
		Gaara\Core\ServiceProvider\Response::class,
		Gaara\Core\ServiceProvider\Tool::class,
		Gaara\Core\ServiceProvider\PhpConsole::class,
		Gaara\Core\ServiceProvider\Secure::class,
		Gaara\Core\ServiceProvider\Session::class,
		Gaara\Core\ServiceProvider\Model::class,
	]

];
