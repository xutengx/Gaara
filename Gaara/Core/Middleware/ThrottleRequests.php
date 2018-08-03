<?php

declare(strict_types = 1);
namespace Gaara\Core\Middleware;

use Gaara\Core\{Cache, Middleware, Request, Response};
use Gaara\Exception\Http\TooManyRequestsHttpException;

/**
 * 访问频率限制
 */
class ThrottleRequests extends Middleware {

	// 访问次数
	protected $accessTimes = 0;
	// 指纹
	protected $key = '';
	// 单位时间内的请求次数
	protected $maxAttempts = 100;
	// 单位时间 (秒)
	protected $decaySecond = 60;

	/**
	 * @param int $maxAttempts 单位时间内的请求次数
	 * @param int $decaySecond 单位时间 (秒)
	 */
	public function __construct($maxAttempts = 100, $decaySecond = 60) {
		$this->maxAttempts = (int)$maxAttempts;
		$this->decaySecond = (int)$decaySecond;
	}

	/**
	 * @param Request $request
	 * @return void
	 * @throws TooManyRequestsHttpException
	 */
	public function handle(Request $request): void {
		// 当前请求指纹
		$this->key = $this->resolveRequestSignature($request);

		// 是否超出限制
		if ($this->tooManyAttempts()) {
			// 返回响应 终止进程
			$this->buildResponse();
		}
		else {
			// 增加响应头 进程继续
			$this->addHeader();
		}
	}

	/**
	 * 计算当前请求的指纹(key), 需要区分用户则请重载此方法
	 * @param Request $request
	 * @return string
	 */
	protected function resolveRequestSignature(Request $request): string {
		$methods  = $request->methods;
		$factor[] = $request->staticUrl;
		$factor[] = $request->userIp;
		// 增加用户区分
		// $factor[] = $request->userinfo['id'];
		return sha1(implode('|', array_merge($methods, $factor)));
	}

	/**
	 * 检测是否已经超过尝试伐值
	 * @return bool
	 */
	protected function tooManyAttempts(): bool {
		// 是否"访问计数器"超过限制 , (Cache::remember方法会在key不存在时生成)
		if (($this->getValue()) >= $this->maxAttempts) {
			return true;
		}
		else {
			// "访问计数器"自增 ,高并发下"redis驱动"会自增一个没有过期时间的值, 不过后面的流程会解决这种情况
			// 2017-11-15 重写缓存驱动时, 将"file驱动"也加上这个逻辑:"自增一个不存在的键,将从0自增,没有过期时间"
			$this->accessTimes = $this->increment($this->decaySecond);
			return false;
		}
	}

	/**
	 * 初始化计数器
	 * @param int $times
	 * @return int
	 */
	protected function getValue(int $times = 0): int {
		return (int)obj(Cache::class)->remember($this->key, $times, $this->decaySecond);
	}

	/**
	 * 访问计数器自增
	 * @return int 返回自增后的值
	 */
	protected function increment(): int {
		return obj(Cache::class)->increment($this->key);
	}

	/**
	 * 增加对应的响应头
	 * 超过请求次数限制,则中断
	 * @return void
	 * @throws TooManyRequestsHttpException
	 */
	protected function buildResponse(): void {
		$retryAfter = obj(Cache::class)->ttl($this->key);
		// 高并发下容错处理
		if ($retryAfter === -1) {
			obj(Cache::class)->rm($this->key);
			$this->getValue(1);
			$this->accessTimes = 1;
			$this->addHeader();
		}
		else {
			$this->addHeader($retryAfter);
			throw new TooManyRequestsHttpException('Too Many Attempts. Try again after ' . $retryAfter . ' seconds');
		}
	}

	/**
	 * 增加响应头
	 * @param int $retryAfter
	 * @return void
	 */
	protected function addHeader(int $retryAfter = null): void {
		obj(Response::class)->header()->set('X-RateLimit-Limit', $this->maxAttempts);
		obj(Response::class)->header()->set('X-RateLimit-Remaining', $this->maxAttempts - $this->accessTimes);
		if (!is_null($retryAfter)) {
			obj(Response::class)->header()->set('Retry-After', $retryAfter);
			obj(Response::class)->header()->set('X-RateLimit-Remaining', 0);
			obj(Response::class)->header()->set('X-RateLimit-Reset', time() + $retryAfter);
		}
	}

}
