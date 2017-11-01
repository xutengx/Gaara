<?php

declare(strict_types = 1);
namespace Gaara\Core\Cache;

interface DriverInterface {

    /**
     * 读取缓存
     * @param string $key 键
     * @return array ['code'=> 200,'data'=>$content] or ['code'=>0]
     */
    public function get($key);

    /**
     * 设置缓存
     * @param string $key 键
     * @param string $velue 值
     * @param int $cacheTime 缓存有效时间
     * @return bool
     */
    public function set($key, $velue, $cacheTime);

    /**
     * 删除单一缓存
     * @param string $key 键
     * @return bool
     */
    public function rm($key);

    /**
     * 清除缓存
     * @param $key
     * @return mixed
     */
    public function clear($key);

    /**
     * 获取缓存
     * @param string    $cachedir 缓存文件地址
     * @param int|false $cacheTime 缓存过期时间
     *
     * @echo string
     * @return array ['code'=> 200,'data'=>$content] or ['code'=>0]
     */
    public function callget($key, $cacheTime);

    /**
     * 设置缓存
     * @param $cachedir
     * @param string $return 函数返回
     * @param int|false $cacheTime 缓存过期时间
     * @return array ['code'=> 200,'data'=>$content] or ['code'=>0]
     */
    public function callset($cachedir, $return, $cacheTime);
}
