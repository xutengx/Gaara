<?php

declare(strict_types = 1);
namespace Gaara\Core\Controller\Traits;

use Closure;
use Exception;
use Gaara\Core\{Cache, Request};

trait CometTrait {

	/**
	 * ajax长轮询
	 * 前端 $.ajaxComet() 可调用
	 * @param Closure $callback return null 则 padding
	 * @param int $timeout 进程padding时间(秒)
	 * @param float $sleep 处理业务的间隔时间(秒)
	 * @return string
	 * @throws Exception
	 */
	protected function ajaxComet(Closure $callback, int $timeout = 30, float $sleep = 0.1): string {
		$cycleTimes           = (int)($timeout / $sleep); // 循环次数
		$microsecond          = (int)($sleep * 1000000); // 间隔微秒
		$endBusinessKey       = $this->endBusinessKey();  // 退出标记 key
		$exclusiveBusinessKey = $this->exclusiveBusinessKey(); // 独占 key
		// 关闭 session
		session_write_close();

		try {
			// 退出业务标记
			if ($this->endBusinessMarker($endBusinessKey, $microsecond)) {
				return $this->success();
			}

			// 独占业务
			if (!$this->exclusiveBusiness($exclusiveBusinessKey, $cycleTimes)) {
				return $this->fail('lock', 423);
			}

			$i = 0;
			while ($i++ < $cycleTimes) {
				// 业务已被标记退出, 则退出
				if ($this->endBusiness($endBusinessKey, $exclusiveBusinessKey)) {
					return $this->success([], 'stop');
				}

				// 业务调用
				if (!is_null($res = $callback())) {
					return $this->success($res);
				}
				usleep($microsecond);
			}
			return $this->fail('timeout', 408);
		} catch (Exception $exc) {
			$this->endBusinessException($endBusinessKey, $exclusiveBusinessKey);
			throw $exc;
		}
	}

	/**
	 * 退出业务标记
	 * @return string
	 * @throws Exception
	 */
	protected function endBusinessKey(): string {
		return $this->resolveRequestSignature() . 'end';
	}

	/**
	 * 生成请求签名
	 * @return string
	 * @throws Exception
	 */
	protected function resolveRequestSignature(): string {
		if (isset($_SESSION)) {
			return session_id() . '|' . static::class . '|';
		}
		else
			throw new Exception('AjaxComet is dependent on Session');
	}

	/**
	 * 独占业务锁
	 * @return string
	 * @throws Exception
	 */
	protected function exclusiveBusinessKey(): string {
		return $this->resolveRequestSignature() . 'start';
	}

	/**
	 * 退出业务标记
	 * @param string $endBusinessKey
	 * @param int $microsecond
	 * @return bool
	 */
	protected function endBusinessMarker(string $endBusinessKey, int $microsecond): bool {
		if (obj(Request::class)->input('action') === 'leave') {
			obj(Cache::class)->set($endBusinessKey, true);
			$i = 0;
			while (!is_null(obj(Cache::class)->get($endBusinessKey)) && $i++ < 3) {
				usleep($microsecond); // 0.1秒
			}
			return true;
		}
		else
			return false;
	}

	/**
	 * 独占业务
	 * @param string $exclusiveBusinessKey
	 * @param int $timeout 进程padding时间(秒)
	 * @return bool
	 */
	protected function exclusiveBusiness(string $exclusiveBusinessKey, int $timeout): bool {
		if (obj(Cache::class)->setnx($exclusiveBusinessKey, true)) { // 未兼容 file
			obj(Cache::class)->set($exclusiveBusinessKey, obj(Request::class)->input('timestamp'),
				$timeout * 2 + 5); // 容错过期时间
			return true;
		}
		elseif (obj(Cache::class)->get($exclusiveBusinessKey) === obj(Request::class)->input('timestamp')) {
			return true;
		}
		return false;
	}

	/**
	 * 退出业务
	 * @param string $endBusinessKey
	 * @param string $exclusiveBusinessKey
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
	 * 异常情况下 退出业务
	 * @param string $endBusinessKey
	 * @param string $exclusiveBusinessKey
	 * @return bool
	 */
	protected function endBusinessException(string $endBusinessKey, string $exclusiveBusinessKey): bool {
		obj(Cache::class)->rm($endBusinessKey);
		obj(Cache::class)->rm($exclusiveBusinessKey);
		return true;
	}

}
