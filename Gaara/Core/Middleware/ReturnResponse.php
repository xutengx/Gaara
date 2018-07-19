<?php

declare(strict_types = 1);
namespace Gaara\Core\Middleware;

use Iterator;
use Generator;
use Gaara\Core\{
	Middleware, Response
};

/**
 * 统一响应处理
 */
class ReturnResponse extends Middleware {

	protected $except = [];

	/**
	 * 兼容不同服务器下, php可能存在的默认缓冲区
	 */
	public function handle() {
		ob_start();
		ob_start();
	}

	/**
	 *
	 * @param Response $response
	 */
	public function terminate(Response $response) {
		// 清除非`Response->send()`输出;
		ob_end_clean();
		// 发送响应
		$response->send();
	}


/**

CREATE TABLE `log` (
  `bigint` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',

  `client_ip` char(15) NOT NULL DEFAULT '' COMMENT '客户端的ip',
	`client_referer` varchar(500) NOT NULL DEFAULT '' COMMENT '客户端的来源页',
	`client_url` varchar(500) NOT NULL DEFAULT '' COMMENT '客户端的完整url',
	`client_file` varchar(200) NOT NULL DEFAULT '' COMMENT '客户端的发送api请求的文件',
	`client_line` int(1) NOT NULL DEFAULT '' COMMENT '客户端的发送api请求的文件行数',
	`client_class_function` varchar(50) NOT NULL DEFAULT '' COMMENT '客户端的发送api请求的类&方法',


  `api_url` varchar(200) NOT NULL DEFAULT '' COMMENT 'api请求的url',
  `api_request_url` varchar(500) NOT NULL DEFAULT '' COMMENT 'api请求的完整url',
  `api_request_method` char(10) NOT NULL DEFAULT '' COMMENT 'api请求的http方法',
  `api_request_data` varchar(2000) NOT NULL DEFAULT '' COMMENT 'api请求的参数, json',

  `api_response_status` int(1) NOT NULL DEFAULT '' COMMENT 'api响应的http状态码',
  `api_response_data` varchar(2000) NOT NULL DEFAULT '' COMMENT 'api响应的数据, json',

  `api_life_time` int(1) NOT NULL DEFAULT '' COMMENT 'api整体耗时, 单位毫秒',

  `description` varchar(2000) NOT NULL COMMENT '描述',
	`created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '记录创建时间',

  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='日志记录表';



 */
	/**
	 * 解码Iterator对象到数组
	 * @param Iterator $obj
	 * @return array
	 */
//	protected function iteratorDecode(Iterator $obj): array {
//		$arr = [];
//		foreach ($obj as $k => $v) {
//			$arr[$k] = $v;
//		}
//		return $arr;
//	}

	/**
	 * 解码Generator对象并直接输出
	 * 一般用于大文件下载
	 * @param Generator $obj
	 * @return void
	 */
//	protected function generatorDecode(Generator $obj): void {
//		foreach ($obj as $v) {
//			echo $v;
//			ob_flush();
//			flush();
//		}
//		exit;
//	}

}
