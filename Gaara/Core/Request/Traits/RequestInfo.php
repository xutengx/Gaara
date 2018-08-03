<?php

declare(strict_types = 1);
namespace Gaara\Core\Request\Traits;

use Gaara\Core\Tool;

/**
 * 请求相关信息获取
 */
trait RequestInfo {

	public $inSys; // 入口文件名 eg:index.php
	public $isAjax; // 是否异步请求
	public $scheme; // https or http
	public $host; // example.com
	public $port; // 443
	public $scriptName; // /index.php
	public $requestUrl; // /admin/index/product?id=100
	public $queryString; //返回 id=100,问号之后的部分。
	public $hostInfo; //返回 http://example.com, 只有host info部分。
	public $absoluteUrl; //返回 http://example.com/admin/index/product?id=100, 包含hos的整个URL。
	public $hostStaticInfo; //返回 http://example.com/, 可以拼接静态资源地址。
	public $pathInfo; //返回 /admin/index/product， 这问号之前（查询字符串）的部分。
	public $staticUrl; //返回 http://example.com/admin/index/product, 包含host pathInfo。
	public $serverName; //返回 example.com, URL中的host name。
	public $method; // 当前http方法
	public $alias; // 当前路由别名
	public $methods; // 当前路由可用的http方法数组
	public $userHost; // 来访者的host
	public $userIp; // 来访者的ip
	public $ip; // 来访者的ip
	public $contentType; // 请求体格式
	public $acceptType; // 需求的相应体格式
	public $MatchedRouting = null; // 路由匹配成功后,由`Kernel`赋值的`MatchedRouting`对象

	//define('HOST', ($_SERVER['HTTP_HTTPS'] ?? $_SERVER['REQUEST_SCHEME']) . '://' . $_SERVER['HTTP_HOST'] . str_replace(IN_SYS, '', $_SERVER['SCRIPT_NAME']));

	public function RequestInfoInit() {
		$this->inSys          = IN_SYS ?? 'index.php';
		$this->isAjax         = $this->isAjax();
		$this->scheme         = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? $_SERVER['REQUEST_SCHEME'];
		$this->host           = $_SERVER['HTTP_HOST'];
		$this->port           = $_SERVER['HTTP_X_FORWARDED_PROT'] ?? $_SERVER['SERVER_PORT'];
		$this->scriptName     = $_SERVER['SCRIPT_NAME'];
		$this->requestUrl     = $_SERVER['REQUEST_URI'];
		$this->queryString    = $_SERVER['QUERY_STRING'];
		$this->hostInfo       = $this->scheme . '://' . $this->host .
		                        (($this->port !== '80' && $this->port !== '443') ? ':' . $this->port : '');
		$this->absoluteUrl    = $this->hostInfo . $this->requestUrl;
		$this->hostStaticInfo = $this->hostInfo . str_replace($this->inSys, '', $this->scriptName);
		$this->pathInfo       = '/' . str_replace('?' . $this->queryString, '',
				substr_replace($this->requestUrl, '', 0, strlen(str_replace($this->inSys, '', $this->scriptName))));
		$this->staticUrl      = $this->hostInfo . $this->pathInfo;
		$this->serverName     = $_SERVER['SERVER_NAME'];
		$this->method         = strtolower($_SERVER['REQUEST_METHOD']);
		$this->userHost       = $_SERVER['REMOTE_HOST'] ?? '';
		$this->ip             = $this->userIp = $this->getUserIp();
		$this->contentType    = $_SERVER['CONTENT_TYPE'] ?? '';
		$this->acceptType     = $_SERVER['ACCEPY_TYPE'] ?? $_SERVER['ACCEPY'] ?? '';
	}

	/**
	 * 是否ajax请求
	 * @return bool
	 */
	protected function isAjax(): bool {
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
		    (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'))
			return true;
		return false;
	}

	/**
	 * 获取客户端ip, 兼容通用nginx配置
	 * 在`客户端欺诈`与`代理设置不合理`的双重情况下, 将会不准确
	 * @return string
	 */
	protected function getUserIp(): string {
		// 准确ip
		// 一级代理赋值 proxy_set_header X-Real-IP $remote_addr;
		// 其他代理传值 proxy_set_header X-Real-IP $http_x_real_ip;
		if (isset($_SERVER['HTTP_X_REAL_IP']) && !empty($_SERVER['HTTP_X_REAL_IP'])) {
			return $_SERVER['HTTP_X_REAL_IP'];
		}

		// 推断ip
		// 返回代理ip列表中的最后个非内网ip, 做为客户端ip
		// 都是内网ip, 则返回第一个ip
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$proxyIps = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			foreach (array_reverse($proxyIps) as $proxyIp) {
				if (!obj(Tool::class)->checkIp($proxyIp)) {
					return $proxyIp;
				}
			}
			return reset($proxyIps);
		}

		// 代理服务器不存在或者未传值
		return $_SERVER['REMOTE_ADDR'];
	}

}
