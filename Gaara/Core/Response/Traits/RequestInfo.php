<?php

declare(strict_types = 1);
namespace Gaara\Core\Response\Traits;

use Gaara\Core\Request;

trait RequestInfo {

	protected static $httpType = [
		'html'		 => ['text/html', 'application/xhtml+xml'],
		'php'		 => ['application/php', 'text/php', 'php'],
		'xml'		 => ['application/xml', 'text/xml', 'application/x-xml'],
		'json'		 => ['application/json', 'text/x-json', 'application/jsonrequest', 'text/json', 'text/javascript'],
		'js'		 => ['text/javascript', 'application/javascript', 'application/x-javascript'],
		'css'		 => ['text/css'],
		'rss'		 => ['application/rss+xml'],
		'yaml'		 => ['application/x-yaml,text/yaml'],
		'atom'		 => ['application/atom+xml'],
		'pdf'		 => ['application/pdf'],
		'text'		 => ['text/plain'],
		'png'		 => ['image/png'],
		'jpg'		 => ['image/jpg,image/jpeg,image/pjpeg'],
		'gif'		 => ['image/gif'],
		'csv'		 => ['text/csv'],
	];

	/**
	 * 获取当前请求的Accept头信息
	 * @return string
	 */
	protected function getAcceptType(): string {
		foreach (self::$httpType as $key => $val) {
			foreach ($val as $v) {
				if (stristr(obj(Request::class)->acceptType, $v)) {
					return $key;
				}
			}
		}
		return 'html';
	}

}
