<?php

declare(strict_types = 1);
namespace Gaara\Core\Secure\Traits;

use Closure;
use Exception;
use Gaara\Core\Cache;

/**
 * 原子性
 */
trait Atomicity {

	/**
	 * 锁定一个闭包
	 * @param string $lock_key
	 * @param Closure $callback
	 * @param int $lock_time
	 * @return bool
	 * @throws Exception
	 */
	public function lock(string $lock_key, Closure $callback, int $lock_time = 20): bool {
		if ($this->lockup($lock_key, $lock_time)) {
			try {
				$callback();
			} catch (Exception $exc) {
				$this->unlock($lock_key);
				throw $exc;
			}
			$this->unlock($lock_key);
			return true;
		}
		return false;
	}

	/**
	 * 加锁
	 * @param string $lock_key
	 * @param int $lock_time
	 * @return bool
	 * @throws Exception
	 */
	public function lockup(string $lock_key, int $lock_time = 20): bool {
		$cache = obj(Cache::class);
		if ($cache->getDirverName() !== 'redis') {
			throw new Exception('lock() is dependent on Redis of Cache ');
		}
		// 当前时刻
		$time = time();
		// 未来`过期时刻`
		$time_out = $time + $lock_time;
		// 尝试获取锁(设置值)
		$res = $cache->setnx($lock_key, $time_out);
		// 锁成功!
		if ($res) {
			return true;
		}
		// 锁失败, 接下来排除一些异常原因导致的锁没有被释放的问题, 其标志为`锁过期`
		else {
			// 得到原锁的`过期时刻`
			$time_in_redis_old_first = $cache->get($lock_key);
			// 原锁已经过期, 或者此刻原锁被删除
			if ((int)$time_in_redis_old_first <= $time) {
				// 重新尝试获取锁(设置值)
				$time_in_redis_old_second = $cache->getset($lock_key, $time_out);
				// 判断是否成功
				if ($time_in_redis_old_second === $time_in_redis_old_first) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * 解锁
	 * @param string $lock_key
	 * @return bool
	 */
	public function unlock(string $lock_key): bool {
		return obj(Cache::class)->rm($lock_key);
	}

}
