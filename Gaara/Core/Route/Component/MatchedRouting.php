<?php

declare(strict_types = 1);
namespace Gaara\Core\Route\Component;

/**
 * 成功匹配的路由对象
 */
class MatchedRouting {

	// 此路由可接受的http方法
	public $methods;
	// 路由中间件组
	public $middlewareGroups;
	// 路由别名
	public $alias;
	// 主体方法
	public $subjectMethod;
	// 域名参数
	public $domainParamter;
	// 静态参数(pathInfo参数)
	public $staticParamter;
	// 路由参数(array_merge($domainParam, $staticParamter))
	public $urlParamter;
	// 中间件
	public $middlewares;

}
