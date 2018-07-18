<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Closure;
use Gaara\Core\Kernel\Traits\Init;

abstract class Kernel extends Container {

	use Init;

	// 调试模式
	public $debug				 = true;
	// 命令行
	public $cli					 = false;
	// 管道对象
	protected $pipeline			 = null;
	// 请求对象
	protected $request			 = null;
	// 全局中间件
	protected $middlewareGlobel	 = [];
	// 路由中间件组
	protected $middlewareGroups	 = [];
	// 缓存自己
	protected static $instance	 = null;

	final protected function __construct() {

	}

	public static function getInstance(): Kernel {
		return static::$instance ?? (static::$instance = new static);
	}

	/**
	 * 执行路由
	 */
	public function start() {
		// 获取路由对象
		$route	 = $this->make(Route::class);
		$request = $this->request;

		// 路由匹配成功
		if ($route->Start()) {
			// 匹配成功对象
			$MR = $route->MatchedRouting;

			// 全局中间件 + 路由中间件
			$MR->middlewares = $this->getMiddlewares($MR->middlewareGroups);

			// request对象设定路由相关
			$request->setMatchedRouting($MR);

			// 执行
			$this->run($MR->subjectMethod, $MR->urlParamter, $MR->middlewares);
		}
		// 是否存在默认的404响应
		elseif (is_null($rule404 = $route->rule404))
			// 默认的404响应
			$this->make(Response::class)->setStatus(404)->setContent('Not Found ..')->sendExit();
		else
			// 设置的404响应
			$this->run($rule404, ['pathinfo' => $request->pathInfo]);
	}

	/**
	 * 将中间件数组, 加入管道流程 Pipeline::pipesPush(string)
	 * @param array $middlewareGroups
	 * @return array
	 */
	protected function getMiddlewares(array $middlewareGroups): array {
		$arr = [];
		// 全局中间件
		foreach ($this->middlewareGlobel as $middleware) {
			$arr[] = $middleware;
		}
		// 路由中间件
		foreach ($middlewareGroups as $middlewareGroup) {
			foreach ($this->middlewareGroups[$middlewareGroup] as $middleware) {
				$arr[] = $middleware;
			}
		}
		return $arr;
	}

	/**
	 * 主题代码
	 * @param string|Closure $subjectMethod
	 * @param array $parameters
	 * @return Closure
	 */
	protected function doSubject($subjectMethod, array $parameters = []): Closure {
		return function () use ($subjectMethod, $parameters) {
			// 形如 'App\index\Contr\IndexContr@indexDo'
			if (is_string($subjectMethod)) {
				$temp = explode('@', $subjectMethod);
				return $this->execute($temp[0], $temp[1], $parameters);
			}
			// 形如 function($param_1, $param_2 ) {return 'this is a function !';}
			elseif ($subjectMethod instanceof Closure) {
				return $this->executeClosure($subjectMethod, $parameters);
			}
		};
	}

	/**
	 * 执行中间件以及用户业务代码
	 * @param string|Closure $subjectMethod
	 * @param array $parameters
	 * @param array $middlewares
	 * @return void
	 */
	protected function run($subjectMethod, array $parameters = [], array $middlewares = []): void {
		$this->statistic();
		$this->pipeline->setPipes($middlewares);
		$this->pipeline->setDefaultClosure($this->doSubject($subjectMethod, $parameters));
		$this->pipeline->then();
	}

	// 运行统计
	protected function statistic(): void {
		$GLOBALS['statistic']['框架路由执行后时间']	 = microtime(true);
		$GLOBALS['statistic']['框架路由执行后内存']	 = memory_get_usage();
	}

}
