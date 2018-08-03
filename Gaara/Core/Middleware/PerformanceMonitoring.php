<?php

declare(strict_types = 1);
namespace Gaara\Core\Middleware;

use Gaara\Core\{Middleware, Response};
use Gaara\Expand\PhpConsole;

/**
 * 性能监控
 */
class PerformanceMonitoring extends Middleware {

	/**
	 * 初始化 PhpConsole, 其__construct 中启用了ob_start, 再手动启用ob_start, 确保header头不会提前发出
	 * 一层ob的情况下当使用ob_end_clean关闭之后的内容若超过web_server(nginx)的输出缓冲大小(默认4k),将会被发送
	 * 受限于http响应头大小,意外输出过多时(大于3000)将会写入文件, 详见\Gaara\Expand\PhpConsole
	 * 使用 PhpConsole 输出的内容不应该超过 3000 字符, 以达到通过浏览器调试的目的;
	 * @param PhpConsole $PhpConsole
	 */
	public function handle(PhpConsole $PhpConsole) { }

	public function terminate(Response $response, PhpConsole $PhpConsole) {

		$info = \statistic();
		$PhpConsole->debug($info, 'PerformanceMonitoring');

		return $response;
	}

}
