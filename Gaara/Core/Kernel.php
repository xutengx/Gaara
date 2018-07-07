<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Closure;
use Exception;
use ReflectionFunction;
use ReflectionClass;

abstract class Kernel {

	// 管道对象
	protected $pipeline			 = null;
	// 全局中间件
	protected $middlewareGlobel	 = [];
	// 路由中间件组
	protected $middlewareGroups	 = [];

	public function __construct(Pipeline $pipeline) {
		$this->pipeline = $pipeline;
	}

	/**
	 * 初始化配置
	 * @return Kernel
	 */
	public function Init(): Kernel {
		$this->ConfInit();
		$this->RequestInit();
		return $this;
	}

	/**
	 * 初始化配置
	 * @return void
	 */
	private function ConfInit() {
		$conf		 = obj(Conf::class)->app;
		$serverIni	 = obj(Conf::class)->getServerConf('php');
		foreach ($serverIni as $k => $v) {
			if (ini_set($k, $v) === false) {
				throw new Exception("ini_set($k, $v) is Fail");
			}
		}
		date_default_timezone_set($conf['timezone']);
		if ($conf['debug'] === '1') {
			ini_set('display_errors', '1');
			error_reporting(E_ALL);
		} else {
			ini_set('display_errors', '0');
		}
	}

	/**
	 * 初始化请求
	 * @return void
	 */
	private function RequestInit(): void {
		obj(Request::class);
	}

	/**
	 * 执行路由
	 */
	public function Start() {
		if (Route::Start()) {
			obj(Request::class)->MatchedRouting	 = $MR									 = Route::$MatchedRouting;
			obj(Request::class)->alias			 = $MR->alias;
			obj(Request::class)->methods		 = $MR->methods;

			obj(Request::class)->setDomainParamters($MR->domainParamter)
			->setStaticParamters($MR->staticParamter)
			->setRequestParamters();
			$MR->middlewares = $this->getMiddlewares($MR->middlewareGroups);

			$this->run($MR->middlewares, $MR->subjectMethod, $MR->urlParamter);
		} else {
			if (is_null($rule404 = Route::$rule404))
				obj(Response::class)->setStatus(404)->setContent('Not Found ..')->sendExit();
			else
				$this->run([], $rule404, ['pathinfo' => obj(Request::class)->pathInfo]);
		}
	}

	/**
	 * 执行中间件以及用户业务代码
	 * @param array $middlewares
	 * @param string|callback|array $subjectMethod
	 * @param array $request
	 * @return void
	 */
	public function run(array $middlewares, $subjectMethod, array $request): void {
		$this->statistic();
		$this->pipeline->setPipes($middlewares);
		$this->pipeline->setDefaultClosure($this->doController($subjectMethod, $request));
		$this->pipeline->then();
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
	 * 方法依赖注入,执行,支持闭包函数
	 * @param string|callback|array $subjectMethod 将要执行的方法
	 * @param array $request 请求参数
	 * @return Closure
	 */
	protected function doController($subjectMethod, array $request): Closure {
		return function () use ($subjectMethod, $request) {
			/**
			 * 方法依赖注入
			 * @param array $parameters 由反射类获取的方法依赖参数链表
			 * @return array 参数数组
			 */
			$injection = function($parameters) use ($request) {
				// 定义实参数组
				$argument = [];
				// 遍历所有形参
				foreach ($parameters as $param) {
					// 判断参数类型 是类
					if ($paramClass = $param->getClass()) {
						// 获得参数类型名称
						$paramClassName	 = $paramClass->getName();
						// 加入对象到参数列表
						$argument[]		 = Integrator::getWithoutAlias($paramClassName);
					} else {
						if (isset($request[$param->name])) {
							// 加入实参到参数列表
							$argument[] = $request[$param->name];
						} else {
							$argument[] = \null;
						}
					}
				}
				return $argument;
			};
			// 形如 'App\index\Contr\IndexContr@indexDo'
			if (is_string($subjectMethod)) {
				$temp			 = explode('@', $subjectMethod);
				$reflectionClass = new ReflectionClass($temp[0]);
				$methodClass	 = $reflectionClass->getMethod($temp[1]);
				$parameters		 = $methodClass->getParameters();

				$argument	 = $injection($parameters);
				$return		 = call_user_func_array(array(Integrator::getWithoutAlias($temp[0]), $temp[1]), $argument);
			}
			// 形如 function($param_1, $param_2 ) {return 'this is a function !';}
			elseif ($subjectMethod instanceof Closure) {
				$reflectionFunction	 = new ReflectionFunction($subjectMethod);
				$parameters			 = $reflectionFunction->getParameters();

				$argument	 = $injection($parameters);
				$return		 = call_user_func_array($subjectMethod, $argument);
			}
			return $return;
		};
	}

	// 运行统计
	private function statistic(): void {
		$GLOBALS['statistic']['框架路由执行后时间']	 = microtime(true);
		$GLOBALS['statistic']['框架路由执行后内存']	 = memory_get_usage();
	}

}
