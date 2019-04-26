<?php

return [
	/*
	  |--------------------------------------------------------------------------
	  | 全局中间件
	  |--------------------------------------------------------------------------
	 */
	'global' => [
		\Gaara\Core\Middleware\ExceptionHandler::class, // 异常处理
		\Gaara\Core\Middleware\ReturnResponse::class, // 移除意外输出,根据http协议返回,\Generator对象分割下载
		\Gaara\Core\Middleware\ValidatePostSize::class, // post请求体大小检测
		\Gaara\Core\Middleware\PerformanceMonitoring::class, // php-console 显示执行性能
	],
	/*
	  |--------------------------------------------------------------------------
	  | 路由中间件
	  |--------------------------------------------------------------------------
	 */
	'groups' => [
		'web'      => [
			\Gaara\Core\Middleware\StartSession::class, // 开启session
			\Gaara\Core\Middleware\VerifyCsrfToken::class, // csrf校验
		],
		'api'      => [
			\Gaara\Core\Middleware\CrossDomainAccess::class, // 允许跨域
			\Gaara\Core\Middleware\ThrottleRequests::class . '@30@60', // 访问频率控制 30次 / 60s
		],
		'admin'    => [
			\Apptest\yh\Middleware\AdminCheck::class // 管理员登入令牌
		],
		'merchant' => [
			\Apptest\yh\Middleware\SignCheck::class // 商户登入令牌
		],
		'payment'  => [
			\Apptest\yh\Middleware\PaymentCheck::class // 商户支付api调用令牌
		]
	],

];
