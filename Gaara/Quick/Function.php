<?php

declare(strict_types = 1);

function app(): \Gaara\Core\Kernel {
	return \App\Kernel::getInstance();
}

/**
 * 实例化对象, 解决依赖
 * @param string $obj
 * @param array $parameters 其他参数, 注:单例模式下,显然只有第一次实例化时,参数才会被使用!
 * @return object
 */
function obj(string $obj, array $parameters = []) {
	return app()->make($obj, $parameters);
}

/**
 * 执行方法, 解决依赖
 * @param string|object $object 类|对象
 * @param string $method
 * @param array $parameters
 * @return mixed
 */
function run($object, string $method, array $parameters = []) {
	return app()->execute($object, $method, $parameters);
}

/**
 * 普通 实例化对象
 * @param string $obj
 * @param mixed ...$params
 * @return mixed 对象
 */
function dobj(string $obj, ...$params) {
	return new $obj(...$params);
}

/**
 * 生成完整url
 * @param string $string 路由
 * @param array $param 参数
 * @return string
 */
function url(string $string = '', array $param = []): string {
	$url = obj(Request::class)->hostInfo . rtrim($string, '/');
	if (!empty($param)) {
		$url .= '?' . http_build_query($param);
	}
	return $url;
}

/**
 * 重定向到指定路由
 * @param string $where 指定路由,如:index/index/indexDo/
 * @param array $pars   跳转中间页显示信息|不使用中间页
 * @param string $msg   参数数组
 * @throws \Exception
 */
function redirect(string $where = '', array $pars = array(), string $msg = null) {
	$url = url($where, $pars);
	!is_null($msg) ? obj(Template::class)->jumpTo($url, $msg) : exit(header('Location:' . $url));
}

/**
 * 读取环境变量
 * @param string $envname
 * @param mixed $default
 * @return mixed
 */
function env(string $envname, $default = null) {
	return obj(\Gaara\Core\Conf::class)->getEnv($envname, $default);
}

/**
 * 运行状态统计
 * @global type $statistic
 * @return array
 */
function statistic(): array {
	global $statistic;
	// 框架初始化消耗时间(含路由)
	$initTime	 = ( $statistic['框架路由执行后时间'] - $statistic['时间初始量'] ) * 1000; //将时间转换为毫秒
	// 框架初始化消耗内存(含路由)
	$initMemory	 = ( $statistic['框架路由执行后内存'] - $statistic['内存初始量'] ) / 1024;
	// 总体消耗时间
	$runtime	 = ( microtime(true) - $statistic['框架路由执行后时间'] ) * 1000; //将时间转换为毫秒
	// 总体消耗内存峰值
	$totleMemory = ( memory_get_peak_usage() ) / 1024;
	// 当前总体消耗内存
	$nowMemory	 = ( memory_get_usage() ) / 1024;
	// 程序消耗内存(当前)
	$now2Memory	 = ( memory_get_usage() - $statistic['框架路由执行后内存']) / 1024;
	// 程序消耗内存(峰值)
	$usedMemory	 = ( memory_get_peak_usage() - $statistic['框架路由执行后内存'] ) / 1024;

	$data = [
		'a.`系统php环境`初始化消耗内存' => round($statistic['内存初始量'] / 1000, 3) . 'K',
		'b.`框架`初始化消耗内存(含路由)' => round($initMemory, 3) . 'K',
		'c.`框架`初始化消耗时间(含路由)' => round($initTime, 3) . '毫秒',
		'd.`业务程序`消耗内存(当前)'	 => round($now2Memory, 3) . 'K',
		'e.`业务程序`消耗内存(峰值)'	 => round($usedMemory, 3) . 'K',
		'f.`总体`消耗内存(当前)'	 => round($nowMemory, 3) . 'K',
		'g.`总体`消耗内存(峰值)'	 => round($totleMemory, 3) . 'K',
		'h.`总体`消耗时间'		 => round($runtime, 3) . '毫秒',
	];

	return $data;
}
