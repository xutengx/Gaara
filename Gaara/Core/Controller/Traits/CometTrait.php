<?php

declare(strict_types = 1);
namespace Gaara\Core\Controller\Traits;

use Gaara\Core\{
	Request, Cache
};
use Exception;
use Closure;

trait CometTrait {

	/**
	 * ajax长轮询
	 * @param Closure $callback  return null 则 padding
	 * @param int $sleep
	 * @param int $timeout
	 * @return type
	 */
	protected function ajaxComet(Closure $callback, int $sleep = 1, int $timeout = 30): string {
		$endBusinessKey			 = $this->endBusinessKey();
		$exclusiveBusinessKey	 = $this->exclusiveBusinessKey();
		// 关闭 session
		session_write_close();

		// 退出业务标记
		if ($this->endBusinessMarker($endBusinessKey)) {
			return $this->success();
		}

		// 独占业务
		if (!$this->exclusiveBusiness($exclusiveBusinessKey, $timeout)) {
			return $this->fail('timeout', 423);
		}

		$i = 0;
		while ($i++ < $timeout) {
			// 业务已被标记退出退出
			if ($this->endBusiness($endBusinessKey, $exclusiveBusinessKey)) {
				return $this->success([], 'stop');
			}

			// 业务调用
			if (!is_null($res = $callback())) {
				return $this->success($res);
			}
			sleep($sleep);
		}
		return $this->fail('timeout', 408);
	}

	/**
	 * 独占业务
	 * @return bool
	 */
	protected function exclusiveBusiness(string $exclusiveBusinessKey, int $timeout): bool {
		if (obj(Cache::class)->setnx($exclusiveBusinessKey, serialize(true))) {
			obj(Cache::class)->set($exclusiveBusinessKey, obj(Request::class)->input('timestamp'), $timeout * 3 + 10);
			return true;
		} elseif (obj(Cache::class)->get($exclusiveBusinessKey) === obj(Request::class)->input('timestamp')) {
			return true;
		}
		return false;
	}

	/**
	 * 退出业务
	 * @return bool
	 */
	protected function endBusiness(string $endBusinessKey, string $exclusiveBusinessKey): bool {
		if (obj(Cache::class)->get($endBusinessKey)) {
			obj(Cache::class)->rm($endBusinessKey);
			obj(Cache::class)->rm($exclusiveBusinessKey);
			return true;
		}
		return false;
	}

	/**
	 * 退出业务标记
	 * @return bool
	 */
	protected function endBusinessMarker(string $endBusinessKey): bool {
		if (obj(Request::class)->input('action')) {
			obj(Cache::class)->set($endBusinessKey, true);
			return true;
		} else
			return false;
	}

	/**
	 * 独占业务锁
	 * @return string
	 */
	protected function exclusiveBusinessKey(): string {
		return $this->resolveRequestSignature() . 'start';
	}

	/**
	 * 退出业务标记
	 * @return string
	 */
	protected function endBusinessKey(): string {
		return $this->resolveRequestSignature() . 'end';
	}

	/**
	 * 生成请求签名
	 * @return string
	 * @throws \Exception
	 */
	protected function resolveRequestSignature(): string {
		if (isset($_SESSION)) {
			return session_id() . '|' . static::class . '|';
		}
		throw new Exception('Need session ');
	}

}
