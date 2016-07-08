<?php
namespace Main\Core\Cache\Driver;
use Main\Core\Cache\DriverInterface;
defined('IN_SYS')||exit('ACC Denied');
class Redis implements DriverInterface {
    // 键名前缀
    private $prefix = 'RedisFor1';

    public function get($key){}
    public function set($key, $velue, $cacheTime=false){}
    public function rm($key){}
    public function clear($key){}
    public function callget($key,$cacheTime){}
    public function callset($cachedir, $echo='',$return){}
}