<?php

declare(strict_types = 1);
namespace Gaara\Core\Middleware;

use Gaara\Core\{Middleware, Request, Response};

/**
 * 允许跨域访问
 * 此中间件, 应该在其他中间件之前执行,
 */
class CrossDomainAccess extends Middleware {

	public function handle(Request $request, Response $response): void {
		if (isset($_SERVER['HTTP_REFERER'])) {
			$response->header()->set('Access-Control-Allow-Credentials', 'true');
			$response->header()->set('Access-Control-Allow-Origin', $this->allowDomain());
		}
		else {
			$response->header()->set('Access-Control-Allow-Credentials', 'false');
			$response->header()->set('Access-Control-Allow-Origin', '*');
		}
		$response->header()
		         ->set('Access-Control-Allow-Headers',
			         'X-Requested-With,scrftoken,Origin, Content-Type, Cookie, Accept, multipart/form-data, application/json');
		$response->header()->set('Access-Control-Allow-Methods', $this->allowMethods($request));
		if ($request->method === 'options') {
			$response->status(200)->sendExit();
		}
	}

	/**
	 * 允许来访的域名
	 * @return string
	 */
	protected function allowDomain(): string {
		// 返回$str中第$num次出现$find的位置
		$getI = function(string $str, int $num, string $find = '/'): int {
			$n = 0;
			for ($i = 1; $i <= $num; $i++) {
				$n = strpos($str, $find, $n);
				$i != $num && $n++;
			}
			return $n;
		};
		// http://git.gitxt.com/git/php_/user/reg  -> http://git.gitxt.com
		return substr($_SERVER['HTTP_REFERER'], 0, $getI($_SERVER['HTTP_REFERER'], 3));
	}

	/**
	 * 返回路由允许的 http 方法
	 * @param Request $request
	 * @return string
	 */
	protected function allowMethods(Request $request): string {
		return strtoupper(implode(',', $request->methods));
	}

}
