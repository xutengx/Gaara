<?php

declare(strict_types = 1);
namespace Gaara\Core\Request\Traits;

/**
 * 请求相关信息获取
 */
trait RequestInfo {

	public $inSys; // 入口文件名 eg:index.php
	public $isAjax; // 是否异步请求
	public $scheme; // https or http
	public $host; // example.com
	public $scriptName; // /index.php
	public $requestUrl; // /admin/index.php/product?id=100
	public $queryString; //返回 id=100,问号之后的部分。
	public $absoluteUrl; //返回 http://example.com/admin/index.php/product?id=100, 包含host infode的整个URL。
	public $hostInfo; //返回 http://example.com, 只有host info部分。
	public $pathInfo; //返回 /admin/index.php/product， 这问号之前（查询字符串）的部分。
	public $staticUrl; //返回 http://example.com/admin/index.php/product, 包含host pathInfo。
	public $serverName; //返回 example.com, URL中的host name。
	public $serverPort; //返回 80, 这是web服务中使用的端口。
	public $method; // 当前http方法
	public $alias; // 当前路由别名
	public $methods; // 当前路由可用的http方法数组
	public $userHost; // 来访者的host
	public $userIp; // 来访者的ip
	public $contentType; // 请求体格式
	public $acceptType; // 需求的相应体格式
	public $MatchedRouting = null; // 路由匹配成功后,由`Kernel`赋值的`MatchedRouting`对象

	public function RequestInfoInit() {
		$this->inSys		 = IN_SYS;
		$this->isAjax		 = $this->isAjax();
		$this->scheme		 = $_SERVER['HTTP_HTTPS'] ?? $_SERVER['REQUEST_SCHEME'];
		$this->host			 = $_SERVER['HTTP_HOST'];
		$this->scriptName	 = $_SERVER['SCRIPT_NAME'];
		$this->requestUrl	 = $_SERVER['REQUEST_URI'];
		$this->queryString	 = $_SERVER['QUERY_STRING'];
		$this->absoluteUrl	 = $this->scheme . '://' . $this->host . $this->requestUrl;
		$this->hostInfo		 = $this->scheme . '://' . $this->host;
		$this->pathInfo		 = '/' . str_replace('?' . $this->queryString, '', substr_replace($this->requestUrl, '', 0, strlen(str_replace($this->inSys, '', $this->scriptName))));
		$this->staticUrl	 = $this->hostInfo . $this->pathInfo;
		$this->serverName	 = $_SERVER['SERVER_NAME'];
		$this->serverPort	 = $_SERVER['SERVER_PORT'];
		$this->method		 = strtolower($_SERVER['REQUEST_METHOD']);
		$this->userHost		 = $_SERVER['REMOTE_HOST'] ?? '';
		$this->userIp		 = $this->getUserIp();
		$this->contentType	 = $_SERVER['CONTENT_TYPE'] ?? '';
		$this->acceptType	 = $_SERVER['ACCEPY_TYPE'] ?? $_SERVER['ACCEPY'] ?? '';
	}

	/**
	 * 是否ajax请求
	 * @return bool
	 */
	private function isAjax(): bool {
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'))
			return true;
		return false;
	}

	/**
	 * 获取客户端ip
	 * @return string
	 */
	private function getUserIp(): string {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$realip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$realip = $_SERVER['REMOTE_ADDR'];
		}
		return $realip;
	}

}
