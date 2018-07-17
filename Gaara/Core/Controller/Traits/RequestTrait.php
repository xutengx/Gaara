<?php

declare(strict_types = 1);
namespace Gaara\Core\Controller\Traits;

use Gaara\Core\Request;
use Gaara\Exception\Http\UnprocessableEntityHttpException;

/**
 * 请求过滤
 */
trait RequestTrait {

	/**
	 * 请求参数获取, 将会中断响应
	 * @param string $key 字段
	 * @param string $rule 验证规则
	 * @param string $msg 验证失败后的文字响应
	 * @param string $fun 要获取的参数,所在的http方法
	 * @return mixed
	 */
	protected function requestFun(string $key = null, string $rule = null, string $msg = null, string $fun = 'get') {
		$request = obj(Request::class);
		if (!is_null($key)) {
			$res = $request->{$fun}($key, $rule);
			if ($res === false) {
				return $this->requestArgumentInvalid($key, $fun, $msg, $rule);
			} elseif ($res === null) {
				return $this->requestArgumentNotFound($key, $fun, $msg, $rule);
			} else
				return $res;
		}else {
			$array = $request->$fun;
			foreach ($array as $k => $v) {
				if (isset($request->filterArr[$k])) {
					$array[$k] = $this->{$fun}($k, $k);
				}
			}
			return $array;
		}
	}

	protected function input(string $key = null, string $rule = null, string $msg = null) {
		return $this->requestFun($key, $rule, $msg, 'input');
	}

	protected function get(string $key = null, string $rule = null, string $msg = null) {
		return $this->requestFun($key, $rule, $msg, 'get');
	}

	protected function put(string $key = null, string $rule = null, string $msg = null) {
		return $this->requestFun($key, $rule, $msg, 'put');
	}

	protected function post(string $key = null, string $rule = null, string $msg = null) {
		return $this->requestFun($key, $rule, $msg, 'post');
	}

	protected function delete(string $key = null, string $rule = null, string $msg = null) {
		return $this->requestFun($key, $rule, $msg, 'delete');
	}

	/**
	 * 定义当参数不合法时的响应
	 * @param string $key
	 * @param string $fun
	 * @param string $msg
	 * @param string $rule
	 * @reutrn void
	 * @throws UnprocessableEntityHttpException
	 */
	protected function requestArgumentInvalid(string $key, string $fun, string $msg, string $rule): void {
		$message = $msg ?? 'Invalid request argument : ' . $key . ' [ Rule : ' . $rule . ' ]';
		throw new UnprocessableEntityHttpException($message);
	}

	/**
	 * 定义当参数不存在时的响应
	 * @param string $key
	 * @param string $fun
	 * @param string $msg
	 * @param string $rule
	 * @reutrn void
	 * @throws UnprocessableEntityHttpException
	 */
	protected function requestArgumentNotFound(string $key, string $fun, string $msg, string $rule): void {
		$message = $msg ?? 'Not found request argument : ' . $key . ' [ Method : ' . $fun . ' ]';
		throw new UnprocessableEntityHttpException($message);
	}

}
