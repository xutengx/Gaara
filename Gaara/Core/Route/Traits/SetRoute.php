<?php

declare(strict_types = 1);
namespace Gaara\Core\Route\Traits;

use Closure;

/**
 * 设置路由
 */
trait SetRoute {

	// 可用的 http 动作
	private static $allowMethod	 = [
		'get', 'post', 'put', 'delete', 'head', 'patch', 'options'
	];
	// 分组时的信息
	private static $group		 = [
		'domain'	 => [],
		'prefix'	 => [],
		'namespace'	 => [],
		'middleware' => [],
	];

//	public static function set404($action): void{
//
//	}

	/**
	 * restful风格申明post,delete,get,put四条路由分别对应controller中的create,destroy,select,update方法
	 * @param string $url
	 * @param string $controller
	 * @return void
	 */
	public static function restful(string $url, string $controller): void {
		self::post($url, $controller . '@create');
		self::delete($url, $controller . '@destroy');
		self::get($url, $controller . '@select');
		self::put($url, $controller . '@update');
	}

	/**
	 * options路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public static function options(string $url, $action): void {
		self::match(['options'], $url, $action);
	}

	/**
	 * post路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public static function post(string $url, $action): void {
		self::match(['post', 'options'], $url, $action);
	}

	/**
	 * get路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public static function get(string $url, $action): void {
		self::match(['get', 'options'], $url, $action);
	}

	/**
	 * put路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public static function put(string $url, $action): void {
		self::match(['put', 'options'], $url, $action);
	}

	/**
	 * delete路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public static function delete(string $url, $action): void {
		self::match(['delete', 'options'], $url, $action);
	}

	/**
	 * head路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public static function head(string $url, $action): void {
		self::match(['head'], $url, $action);
	}

	/**
	 * patch路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public static function patch(string $url, $action): void {
		self::match(['patch'], $url, $action);
	}

	/**
	 * 任意http方法路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public static function any(string $url, $action): void {
		self::match(self::$allowMethod, $url, $action);
	}

	/**
	 * 处理分析每个路由以及所在组环境, 并加入 self::$routeRule
	 * @param array $method 可以匹配的http方法数组
	 * @param string $url 路由
	 * @param mix $action
	 * @return void
	 */
	public static function match(array $method, string $url, $action): void {
		// 格式化action
		$actionInfo = self::formatAction($action);

		// 处理得到 url
		{
			if (!empty(self::$group['prefix'])) {
				$prefix = '';
				foreach (self::$group['prefix'] as $v) {
					if (!empty($v))
						$prefix .= '/' . $v;
				}
				$url = $prefix . $url;
			}
		}

		// 处理得到 完整uses
		{
			if ($actionInfo['uses'] instanceof Closure) {
				$uses = $actionInfo['uses'];
			} else {
				$group_namespace = '';
				foreach (self::$group['namespace'] as $v) {
					if (!empty($v))
						$group_namespace .= str_replace('/', '\\', $v) . '\\';
				}
				$namespace	 = !empty($actionInfo['namespace']) ? str_replace('/', '\\', $actionInfo['namespace']) . '\\'
					: '';
				$uses		 = $group_namespace . $namespace . $actionInfo['uses'];
			}
		}

		// 得到 as 别名
		{
			$as = $actionInfo['as'];
		}

		// 处理得到 最终 domain
		{
			$domain = $_SERVER['HTTP_HOST'];
			if (!empty($actionInfo['domain'])) {
				$domain = $actionInfo['domain'];
			} elseif (!empty(self::$group['domain'])) {
				foreach (self::$group['domain'] as $v) {
					if (!empty($v))
						$domain = $v;
				}
			}
		}

		// 处理得到 完整 middleware
		{
			$middleware = [];
			if (!empty(self::$group['middleware'])) {
				foreach (self::$group['middleware'] as $v) {
					if (empty($v))
						continue;
					$middleware = array_merge($middleware, $v);
				}
			}
			$middleware = array_merge($middleware, $actionInfo['middleware']);
		}
		self::$routeRule[] = [
			$url => [
				'method'	 => $method,
				'middleware' => $middleware,
				'domain'	 => $domain,
				'as'		 => $as,
				'uses'		 => $uses
			]
		];
	}

	/**
	 * 路由分组, 无线级嵌套
	 * @param array $rule
	 * @param Closure $callback
	 * @return void
	 */
	public static function group(array $rule, Closure $callback): void {
		// 当前 group 分组信息填充
		self::$group['middleware'][] = $rule['middleware'] ?? [];
		self::$group['namespace'][]	 = $rule['namespace'] ?? '';
		self::$group['prefix'][]	 = $rule['prefix'] ?? '';
		self::$group['domain'][]	 = $rule['domain'] ?? '';

		// 执行闭包
		$callback();

		// 执行完当前 group 后 移除当前分组信息
		foreach (self::$group as $k => $v) {
			array_pop(self::$group[$k]);
		}
	}

	/**
	 * 格式化 action 参数
	 * @param mix $action
	 * @return array
	 */
	private static function formatAction($action): array {
		$actionInfo = [];
		if (is_array($action)) {
			if ($action['uses'] instanceof Closure) {
				$actionInfo['uses'] = $action['uses'];
			} elseif (is_string($action['uses'])) {
				$actionInfo['uses'] = trim(str_replace('/', '\\', $action['uses']), '\\');
			}
			$actionInfo['middleware']	 = $action['middleware'] ?? [];
			$actionInfo['namespace']	 = $action['namespace'] ?? '';
			$actionInfo['prefix']		 = $action['prefix'] ?? '';
			$actionInfo['as']			 = $action['as'] ?? null;
			$actionInfo['domain']		 = $action['domain'] ?? '';
		} else {
			if ($action instanceof Closure) {
				$actionInfo['uses'] = $action;
			} elseif (is_string($action)) {
				$actionInfo['uses'] = trim(str_replace('/', '\\', $action), '\\');
			}
			$actionInfo['middleware']	 = [];
			$actionInfo['namespace']	 = '';
			$actionInfo['prefix']		 = '';
			$actionInfo['as']			 = null;
			$actionInfo['domain']		 = '';
		}
		return $actionInfo;
	}

}
