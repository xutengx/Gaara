<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Closure;
use PDOException;
use Exception;
use Response;
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
	 * @return string
	 */
	protected function returnData($content = ''): string {
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
	 * 返回一个失败的响应
	 * @param string $msg 错误消息提示
	 * @param int $statusCode http状态码
	 * @return string
	 */
	protected function fail(string $msg = 'Fail', int $statusCode = 400): string {
		return Response::setStatus($statusCode)->returnData(['msg' => $msg]);
	}

	/**
	 * 返回一个正确的响应
	 * @param mixed $data 主要返回内容
	 * @param string $msg 正确消息提示
	 * @param int $statusCode http状态码
	 * @return string
	 */
	protected function success($data = [], string $msg = 'Success', int $statusCode = 200): string {
		return Response::setStatus($statusCode)->returnData(['data' => $data, 'msg' => $msg]);
	}

}
