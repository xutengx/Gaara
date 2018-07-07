<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Closure;
use PDOException;
use Exception;
use Gaara\Core\Controller\Traits\{
	RequestTrait, ViewTrait
};

abstract class Controller {

	// 可以使用 $this->post('id', '/^1[3|4|5|7|8][0-9]\d{8}$/', 'id不合法!'); 过滤参数
	use RequestTrait,
	// 可以使用 $this->display(); 展示视图
	 ViewTrait;

	/**
	 * 返回一个data响应,当接收的参数是Closure时,会捕获PDOException异常,一旦捕获成功,将返回msg响应
	 * @param mixed $content 响应内容
	 * @return Response
	 */
	protected function returnData($content = ''): Response {
		if ($content instanceof Closure) {
			try {
				$content = call_user_func($content);
			} catch (PDOException $pdo) {
				return $this->fail($pdo->getMessage());
			} catch (Exception $e) {
				return $this->fail($e->getMessage());
			}
		}
		if ($content === false || $content === null || $content === 0 || $content === -1)
			return $this->fail();
		return $this->success($content);
	}

	/**
	 * 返回一个失败的`Response`
	 * @param string $msg 错误消息提示
	 * @param int $httpCode http状态码
	 * @return Response
	 */
	protected function fail(string $msg = 'Fail', int $httpCode = 400): Response {
		return obj(Response::class)->fail($msg, $httpCode);
	}

	/**
	 * 返回一个正确的`Response`
	 * @param mixed $data 主要返回内容
	 * @param string $msg 正确消息提示
	 * @param int $httpCode http状态码
	 * @return Response
	 */
	protected function success($data = [], string $msg = 'Success', int $httpCode = 200): Response {
		return obj(Response::class)->success($data, $msg, $httpCode);
	}

}
