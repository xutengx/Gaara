<?php

declare(strict_types = 1);
namespace Gaara\Core\Route\Traits;

use Closure;

/**
 * 设置路由
 */
trait SetRoute {

	// 可用的 http 动作
	private $allowMethod = [
		'get', 'post', 'put', 'delete', 'head', 'patch', 'options'
	];
	// 分组时的信息
	private $group		 = [
		'domain'	 => [],
		'prefix'	 => [],
		'namespace'	 => [],
		'middleware' => [],
	];

	public function set404($action): void {
		$this->rule404 = $action;
	}

	/**
	 * restful风格申明post,delete,get,put四条路由分别对应controller中的create,destroy,select,update方法
	 * @param string $url
	 * @param string $controller
	 * @return void
	 */
	public function restful(string $url, string $controller): void {
		$this->post($url, $controller . '@create');
		$this->delete($url, $controller . '@destroy');
		$this->get($url, $controller . '@select');
		$this->put($url, $controller . '@update');
	}

	/**
	 * options路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public function options(string $url, $action): void {
		$this->match(['options'], $url, $action);
	}

	/**
	 * post路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public function post(string $url, $action): void {
		$this->match(['post', 'options'], $url, $action);
	}

	/**
	 * get路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public function get(string $url, $action): void {
		$this->match(['get', 'options'], $url, $action);
	}

	/**
	 * put路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public function put(string $url, $action): void {
		$this->match(['put', 'options'], $url, $action);
	}

	/**
	 * delete路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public function delete(string $url, $action): void {
		$this->match(['delete', 'options'], $url, $action);
	}

	/**
	 * head路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public function head(string $url, $action): void {
		$this->match(['head'], $url, $action);
	}

	/**
	 * patch路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public function patch(string $url, $action): void {
		$this->match(['patch'], $url, $action);
	}

	/**
	 * 任意http方法路由
	 * @param string $url
	 * @param mix $action
	 * @return void
	 */
	public function any(string $url, $action): void {
		$this->match($this->allowMethod, $url, $action);
	}

	/**
	 * 处理分析每个路由以及所在组环境, 并加入 $this->routeRule
	 * @param array $method 可以匹配的http方法数组
	 * @param string $url 路由
	 * @param mix $action
	 * @return void
	 */
	public function match(array $method, string $url, $action): void {
		// 格式化action
		$actionInfo = $this->formatAction($action);

		// 处理得到 url
		{
			if (!empty($this->group['prefix'])) {
				$prefix = '';
				foreach ($this->group['prefix'] as $v) {
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
				foreach ($this->group['namespace'] as $v) {
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
			} elseif (!empty($this->group['domain'])) {
				foreach ($this->group['domain'] as $v) {
					if (!empty($v))
						$domain = $v;
				}
			}
		}

		// 处理得到 完整 middleware
		{
			$middleware = [];
			if (!empty($this->group['middleware'])) {
				foreach ($this->group['middleware'] as $v) {
					if (empty($v))
						continue;
					$middleware = array_merge($middleware, $v);
				}
			}
			$middleware = array_merge($middleware, $actionInfo['middleware']);
		}
		$this->routeRule[] = [
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
	public function group(array $rule, Closure $callback): void {
		// 当前 group 分组信息填充
		$this->group['middleware'][] = $rule['middleware'] ?? [];
		$this->group['namespace'][]	 = $rule['namespace'] ?? '';
		$this->group['prefix'][]	 = $rule['prefix'] ?? '';
		$this->group['domain'][]	 = $rule['domain'] ?? '';

		// 执行闭包
		$callback();

		// 执行完当前 group 后 移除当前分组信息
		foreach ($this->group as $k => $v) {
			array_pop($this->group[$k]);
		}
	}

	/**
	 * 格式化 action 参数
	 * @param mix $action
	 * @return array
	 */
	private function formatAction($action): array {
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
