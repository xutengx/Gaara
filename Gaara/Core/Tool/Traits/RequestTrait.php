<?php

declare(strict_types = 1);
namespace Gaara\Core\Tool\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

/**
 * 发送请求
 * GuzzleHttp相关文档 http://guzzle-cn.readthedocs.io/zh_CN/latest/quickstart.html
 */
trait RequestTrait {

	/**
	 * 异步执行
	 * @param string    $where  指定路由,如:index/index/indexDo
	 * @param array     $pars   参数数组
	 * @param string    $scheme http/https
	 * @param string    $host   异步执行的服务器ip
	 * @return true
	 */
	public static function asynExe(string $where = '', array $pars = array(), string $scheme = 'http', string $host = '127.0.0.1'): bool {
		$where	 = str_replace('\\', '/', $where);
		$host	 = $scheme . '://' . $host . str_replace(IN_SYS, '', $_SERVER['SCRIPT_NAME']);
		$url	 = $host . ltrim($where, '/');
		if (!empty($pars)) {
			$url .= '?' . http_build_query($pars);
		}
		$this->sendGet($url);
		return true;
	}

	/**
	 * 并行请求
	 * @param array $urls 需要请求的url组成数组 eg ['www.baidu.com','test.xuteng.com?testpar=123']
	 * @param bool $allinfo 是否返回全部信息 eg true 返回`实现了一个PSR-7接口的Response对象`组成的数组, false 返回`响应体`组成的数组
	 * @return array 每个请求的响应体
	 */
	public static function parallelExe(array $urls, bool $allinfo = false): array {
		$client		 = new Client();
		$promises	 = [];
		foreach ($urls as $k => $v) {
			$promises[$k] = $client->getAsync($v);
		}
		// Wait on all of the requests to complete.
		$results = Promise\unwrap($promises);

		if ($allinfo) {
			return $results;
		} else {
			$content = [];
			foreach ($results as $k => $v) {
				$content[$k] = $v->getBody()->getContents();
			}
			return $content;
		}
	}

	/**
	 * 发送post请求
	 * @param string $url
	 * @param array $data
	 * @return string
	 */
	public static function sendPost(string $url, array $data = array()): string {
		$client		 = new Client();
		$response	 = $client->request('POST', $url, [
			'query' => $data
		]);
		return $response->getBody()->getContents();
	}

	/**
	 * 发送get请求
	 * @param string $url
	 * @param array $data
	 * @return string
	 */
	public static function sendGet(string $url, array $data = array()): string {
		$client		 = new Client();
		$response	 = $client->request('GET', $url, [
			'query' => $data
		]);
		return $response->getBody()->getContents();
	}

}
