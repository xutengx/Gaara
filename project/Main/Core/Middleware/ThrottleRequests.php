<?php

declare(strict_types = 1);

namespace Main\Core\Middleware;

use Main\Core\Middleware;
use Main\Core\Request;
use Cache;
use Route;
defined('IN_SYS') || exit('ACC Denied');

/**
 * 访问频率限制
 */
class ThrottleRequests extends Middleware {

    // 访问次数
    protected $accessTimes = 0;
    // 指纹
    protected $key = '';
    // 单位时间内的请求次数
    protected $maxAttempts = 1000;
    // 单位时间 (秒)
    protected $decaySecond = 60;

    public function __construct($maxAttempts = 100, $decaySecond = 60) {
        $this->maxAttempts = $maxAttempts;
        $this->decaySecond = $decaySecond;
    }
    
    /**
     * 
     * @param Request $request      当前请求对象
     * @param type $maxAttempts     单位时间内的请求次数
     * @param type $decaySecond     单位时间 (秒)
     * @return void
     */
    public function handle(Request $request): void {
        // 当前请求指纹
        $this->key = $this->resolveRequestSignature($request);

        // 是否超出限制
        if ($this->tooManyAttempts()) {
            // 返回响应 终止进程
            $this->buildResponse();
        } else {
            // 增加响应头 进程继续
            $this->addHeader();
        }
    }

    /**
     * 初始化计数器
     * @praram int  $times 
     * @return int
     */
    protected function getValue(int $times = 0): int {
        return Cache::get($this->key, $times, $this->decaySecond);
    }

    /**
     * 检测是否已经超过尝试伐值
     * @return bool
     */
    protected function tooManyAttempts(): bool {
        // 是否"访问计数器"超过限制 , (Cache::get方法会在key不存在时生成)
        if (($this->getValue()) >= $this->maxAttempts) {
            return true;
        } else {
            // "访问计数器"自增 ,高并发下 会自增一个没有过期时间的值, 不过后面的流程会解决这种情况
            $this->accessTimes = $this->increment($this->decaySecond);
            return false;
        }
    }

    /**
     * 返回429响应头
     * @param string $key
     * @param int $maxAttempts
     */
    protected function buildResponse() {
        $retryAfter = Cache::ttl($this->key);
        // 高并发下容错处理
        if ($retryAfter === -1) {
            Cache::rm($this->key);
            $this->getValue(1);
            $this->accessTimes = 1;
            $this->addHeader();
        } else {
            $this->addHeader($retryAfter);
            \Response::returnData('Too Many Attempts. Try again after ' . $retryAfter . ' seconds', false, 429);
        }
    }

    /**
     * 访问计数器自增
     * @return int
     */
    protected function increment(): int {
        return \Cache::incrby($this->key, 1);
    }

    /**
     * 增加响应头
     * @param int $retryAfter
     */
    protected function addHeader(int $retryAfter = null): void {
        $headers = [
            'X-RateLimit-Limit' => $this->maxAttempts,
            'X-RateLimit-Remaining' => $this->maxAttempts - $this->accessTimes,
        ];
        if (!is_null($retryAfter)) {
            $headers['X-RateLimit-Remaining'] = 0;
            $headers['Retry-After'] = $retryAfter;
            $headers['X-RateLimit-Reset'] = time() + $retryAfter;
        }
        \Response::setHeaders($headers);
    }

    /**
     * 计算当前请求的指纹(key), 需要区分用户则请重载次方法
     * @param Request $request
     * @return string
     */
    protected function resolveRequestSignature(Request $request): string {
        $url = $request->urlWithoutQueryString;
        $methods = Route::getMethods();
        $ip = $request->ip;
        return sha1(implode('|', array_merge($methods, [$url, $ip])));
    }
}
