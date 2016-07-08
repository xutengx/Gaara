<?php
namespace Main\Core\Cache;
interface DriverInterface{
    public function get($key);
    public function set($key, $velue, $cacheTime=false);
    public function rm($key);
    public function clear($key);

    /**
     * 获取缓存
     * @param string    $cachedir 缓存文件地址
     * @param int|false $cacheTime 缓存过期时间
     *
     * @return array|bool
     */
    public function callget($key,$cacheTime);
    public function callset($cachedir, $echo='',$return);
}