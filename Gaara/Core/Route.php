<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Closure;
use Generator;
use Gaara\Contracts\Route\Registrar;
use Gaara\Core\Route\Traits\SetRoute;
use Gaara\Core\Route\Component\MatchedRouting;
use Gaara\Contracts\ServiceProvider\Single;

/**
 * 显式自定义路由
 */
class Route implements Registrar, Single {

	// 分组以及静态方法申明路由
	use SetRoute;

	// 路由配置成功后对象
	public $MatchedRouting;
	// 404
	public $rule404		 = null;
	// 当前 $pathInfo
	protected $pathInfo	 = null;
	// 全部路由规则
	protected $routeRule = [];

	/**
	 * 路由匹配
	 * @return bool
	 */
	public function Start(): bool {
		// 得到 $pathInfo
		$this->pathInfo	 = $this->getPathInfo();
		// 引入route规则
		$this->routeRule = $this->getRouteRule();
		// 分析路由, 并执行
		return $this->routeAnalysis();
	}

	/**
	 * 分析url,得到pathinfo
	 * eg:http://192.168.64.128/git/php_/project/user/login/123/11?id=12 -> /user/login/123/11
	 * eg:http://git.gitxt.com/data/upload?id=123 -> /data/upload
	 * @return string
	 */
	protected function getPathInfo(): string {
		return obj(Request::class)->pathInfo;
	}

	/**
	 * 得到当前应该使用的route配置
	 * 可以接收直接返回的数组格式, 也可以直接执行
	 * @return array
	 */
	protected function getRouteRule(): array {
		$file		 = obj(Conf::class)->route['file'];
		$fileRule	 = require(ROUTE . $file);
		return is_array($fileRule) ? array_merge($this->routeRule, $fileRule) : $this->routeRule;
	}

	/**
	 * 路由分析, 包含最终执行
	 * 路由匹配失败, 则响应404
	 * @return bool
	 */
	protected function routeAnalysis(): bool {
		foreach ($this->pretreatment() as $rule => $info) {
			// 形参数组
			$parameter		 = [];
			$pathInfoPreg	 = $this->ruleToPreg($rule, $parameter);
			// 确定路由匹配
			if (preg_match($pathInfoPreg, $this->pathInfo, $argument)) {
				// 确认 url 参数
				$staticParamter	 = $this->paramAnalysis($parameter, $argument);
				// 执行分析
				$check			 = $this->infoAnalysis($rule, $info, $staticParamter);
				// 域名不匹配, 则继续 foreach
				if ($check === false)
					continue;
				return true;
			}
		}
		return false;
	}

	/**
	 * 预处理路由数组信息
	 * @return Generator
	 */
	protected function pretreatment(): Generator {
		foreach ($this->routeRule as $rule => $info) {
			// 兼容式路由
			if (is_int($rule)) {
				if (is_null($info)) {
					continue;
				}
				$rule	 = key($info);
				$info	 = reset($info);
			}
			yield $rule => $info;
		}
	}

	/**
	 * 将路由规则翻译为正则表达式
	 * @param string $rule url规则
	 * @param array &$param url上的形参组成的一维数组
	 * @return string 正则表达式
	 * @return array $param 形参数组
	 */
	protected function ruleToPreg(string $rule = '', array &$param = []): string {
		$temp = explode('/', $rule);
		foreach ($temp as $k => $v) {
			$key		 = false;
			$temp[$k]	 = \preg_replace_callback("/{.*\?}/is", function($matches) use (&$param, &$key) {
				$param[] = trim(trim($matches[0], '?}'), '{');
				$key	 = true;
				return '?(/[^/]*)?';
			}, $v);
			if ($key)
				continue;
			$temp[$k] = \preg_replace_callback("/{.*}/is", function($matches) use (&$param) {
				$param[] = trim(trim($matches[0], '}'), '{');
				return '([^/]+)';
			}, $v);
		}
		return '#^' . implode('/', $temp) . '[/]?$#';
	}

	/**
	 * url 参数分析
	 * @param array $parameter 形参数组列表(一维数组)
	 * @param array $argument 实参数组列表(一维数组)
	 * @return array 可调用的参数数组(一维链表)
	 */
	protected function paramAnalysis(array $parameter, array $argument): array {
		$arr = [];
		foreach ($parameter as $k => $v) {
			// 当实参不全时, 填充为 null
			$argument[$k + 1]	 = !isset($argument[$k + 1]) ? '' : $argument[$k + 1];
			$arr[$v]			 = ($argument[$k + 1] === '') ? \null : ltrim($argument[$k + 1], '/');
		}
		return $arr;
	}

	/**
	 * 执行分析 : 路由别名, 域名分析, 中间件注册, 执行闭包
	 * @param string $rule 路由匹配段
	 * @param string|array $info 路由执行段 (可能是形如 'App\index\Contr\IndexContr@indexDo' 或者 闭包, 或者 数组包含以上2钟)
	 * @param array $staticParamter 静态参数(pathInfo参数)
	 * @return bool
	 */
	protected function infoAnalysis(string $rule, $info, array $staticParamter = []): bool {
		// 一致化格式
		$info = $this->unifiedInfo($info);

		// 域名分析
		if (!is_array($domainParamter = $this->domainToPreg($info['domain'])))
			return false;

		// http方法分析
		if (!in_array(strtolower(obj(Request::class)->method), $info['method'], true))
			return false;

		$MR						 = $this->MatchedRouting	 = new MatchedRouting;
		$MR->alias				 = $info['as'] ?? $rule;
		$MR->middlewareGroups	 = $info['middleware'];
		$MR->methods			 = $info['method'];
		$MR->subjectMethod		 = $info['uses'];
		$MR->domainParamter		 = $domainParamter;
		$MR->staticParamter		 = $staticParamter;
		$MR->urlParamter		 = array_merge($domainParamter, $staticParamter);
		return true;
	}

	/**
	 * info 一致化格式
	 * @param mixed $info
	 * @return void
	 */
	protected function unifiedInfo($info): array {
		$arr = [];
		if (is_string($info) || $info instanceof Closure) {
			$arr = [
				'method'	 => $this->allowMethod,
				'middleware' => [],
				'domain'	 => obj(Request::class)->host,
				'as'		 => null,
				'uses'		 => $info
			];
		} elseif (is_array($info)) {
			$arr = [
				'method'	 => $info['method'] ?? $this->allowMethod,
				'middleware' => $info['middleware'] ?? [],
				'domain'	 => $info['domain'] ?? obj(Request::class)->host,
				'as'		 => $info['as'] ?? null,
				'uses'		 => $info['uses']
			];
		}
		return $arr;
	}

	/**
	 * 将域名规则翻译为正则表达式 (不支持问号参数)
	 * @param string $rule 域名规则 eg: {admin}.{gitxt}.com
	 * @return array|false
	 */
	protected function domainToPreg(string $rule = '') {
		$param	 = [];
		$preg	 = \preg_replace_callback("/{[^\.]*}/is", function($matches) use (&$param) {
			$param[trim(trim($matches[0], '}'), '{')] = null;
			return '([^\.]+)';
		}, $rule);
		$preg	 = '#^' . $preg . '$#';
		$key	 = \preg_replace_callback($preg, function($matches) use (&$param) {
			$i = 1;
			foreach ($param as $k => $v) {
				$param[$k] = $matches[$i++];
			}
			return 'true';
		}, obj(Request::class)->host);
		// 若匹配失败 则返回false
		if ($key !== 'true') {
			return false;
		}
		return $param;
	}

}
