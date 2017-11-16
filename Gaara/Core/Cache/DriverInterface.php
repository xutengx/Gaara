<?php

declare(strict_types = 1);
namespace Gaara\Core\Cache;

interface DriverInterface {

    /**
     * 读取缓存
     * @param string $key 键
     * @return string|false
     */
    public function get(string $key);

    /**
     * 设置缓存
     * @param string $key 键
     * @param string $value 值
     * @param int $expire 缓存有效时间 , -1表示无过期时间
     * @return bool
     */
    public function set(string $key, string $value, int $expire): bool;

    /**
     * 删除单一缓存
     * @param string $key 键
     * @return bool
     */
    public function rm(string $key): bool;

    /**
     * 批量清除缓存
     * @param string $key
     * @return bool
     */
    public function clear(string $key): bool;
    
    /**
     * 得到一个key的剩余有效时间
     * @param string $key
     * @return int 0表示过期, -1表示无过期时间, -2表示未找到key
     */
    public function ttl(string $key): int;
}
