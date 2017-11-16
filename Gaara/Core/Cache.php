<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Gaara\Core\Conf;
use Gaara\Core\Cache\Driver;
use Gaara\Core\Cache\Traits;
use Closure;
use InvalidArgumentException;

class Cache {

    use Traits\Remember;

    // 默认缓存更新时间秒数
    public $expire = 3600;
    // 缓存键标识
    public $key = null;
    // 已经支持的驱动
    public $supportedDrivers = [
        'redis' => Driver\Redis::class,
        'file' => Driver\File::class
    ];
    // 配置项
    private $conf = [];
    // 当前驱动
    private $driver;
    // 缓存驱动池
    private $Drivers = [];

    public function __construct(Conf $conf) {
        $this->conf = $conf->cache;
        $this->expire = $this->conf['expire'] ?? $this->expire;
        $this->store();
    }
    
    /**
     * 指定使用的缓存驱动
     * @param string $drivername 
     * @return \Gaara\Core\Cache
     * @throws InvalidArgumentException
     */
    public function store(string $drivername = null): Cache {
        $drivername = $drivername ?? $this->conf['driver'];
        if (array_key_exists($drivername, $this->supportedDrivers)) {
            if (array_key_exists($drivername, $this->Drivers)) {
                $this->driver = $this->Drivers[$drivername];
            } else
                $this->driver = $this->Drivers[$drivername] = new $this->supportedDrivers[$drivername]($this->conf[$drivername]);
        } else
            throw new InvalidArgumentException('Not supported the cache driver : ' . $drivername . '.');
        return $this;
    }

    /**
     * 获取一个缓存
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key) {
        return ($content = $this->driver->get($key)) ? unserialize($content) : null;
    }

    /**
     * 设置一个缓存
     * @param string $key
     * @param mixed $value 闭包不可被序列化,将会执行
     * @param int $expire 有效时间, -1表示不过期
     * @return bool
     */
    public function set(string $key, $value, int $expire = null): bool {
        if ($value instanceof Closure) {
            $value = $value();
        }
        return $this->driver->set($key, serialize($value), $expire ?? $this->expire);
    }
    
    /**
     * 获取&存储
     * 如果键不存在时,则依据上下文生成自动键
     * 如果请求的键不存在时给它存储一个默认值
     * @param mixed ...$params
     * @return mixed
     */
    public function remember(...$params) {
        if (reset($params) instanceof Closure)
            return $this->rememberClosureWithoutKey(false, ...$params);
        else
            return $this->rememberEverythingWithKey(false, ...$params);
    }
    
    
    /**
     * 获取&存储
     * 给它存储一个默认值并返回
     * @param mixed ...$params
     * @return mixed
     */
    public function dremember(...$params){
        if (reset($params) instanceof Closure)
            return $this->rememberClosureWithoutKey(true, ...$params);
        else
            return $this->rememberEverythingWithKey(true, ...$params);
        
    }

    /**
     * 自增
     * 当$key不存在时,将以 $this->set($key, 0, -1); 初始化
     * @param string $key
     * @param int $amount
     * @return int 自增后的值
     */
    public function increment(string $key, int $amount = 1) : int{
        $value = $this->get($key);
        $new_value = is_null($value) ? 0 : (int)$value + abs($amount);
        
        $expire = $this->ttl($key);
        $new_expire = ( $expire === -2 ) ? -1 : $expire;
        if($this->set($key, $new_value, $new_expire)){
            return $new_value;
        }else
            throw new Exception ('Cache Increment Error!');
    }

    /**
     * 自减
     * @param string $key
     * @param int $amount
     * @return int 自减后的值
     */
    public function decrement(string $key, int $amount = 1) : int{
        $value = $this->get($key);
        $new_value = is_null($value) ? 0 : (int)$value - abs($amount);
        
        $expire = $this->ttl($key);
        $new_expire = ( $expire === -2 ) ? -1 : $expire;
        if($this->set($key, $new_value, $new_expire)){
            return $new_value;
        }else
            throw new Exception ('Cache Decrement Error!');
    }
    
    /**
     * 删除单个key
     * @param string $key
     * @return bool
     */
    public function rm(string $key): bool {
        return $this->driver->rm($key);
    }

    /**
     * 删除call方法的缓存
     * @param string|object $obj
     * @param string $func
     * @param mixed ...$params
     * @return bool
     */
    public function clear($obj, string $func = '', ...$params): bool {
        $key = $this->makeKey($obj, $func, $params);
        return $this->driver->clear($key);
    }

    /**
     * 清除当前驱动的全部缓存
     * 清除缓存并不管什么缓存键前缀，而是从缓存系统中移除所有数据，所以在使用这个方法时如果其他应用与本应用有共享缓存时需要格外注意
     * @return bool
     */
    public function flush(): bool {
        return $this->driver->clear('');
    }
    
    /**
     * 获取当前驱动的类名称
     * @return string
     */
    public function getDirverName(): string{
        return get_class($this->driver);
    }
    
    /**
     * 生成键名
     * @param string|object $obj
     * @param string $funcname
     * @param array $params
     * @return string
     * @throws Exception
     */
    private function makeKey($obj, string $funcname = '', array $params = []): string {
        $classname = is_object($obj) ? get_class($obj) : $obj;
        $key = '';                   // default
        if (!empty($params)) {
            foreach ($params as $v) {
                if (is_object($v))
                    throw new InvalidArgumentException('the object is not supported as the parameter in Cache::call. ');
                if ($v === true)
                    $key .= '_bool-t';
                elseif ($v === false)
                    $key .= '_bool-f';
                else
                    $key .= '_' . gettype($v) . '-' . (is_array($v) ? serialize($v) : $v);
            }
            $key = '/' . md5($key);
        }
        $str = $classname . '/' . $funcname . $key;
        $str = is_null($this->key) ? $str : '@' . $this->key . '/' . $str;
        return str_replace('\\', '/', $str);
    }

    /**
     * 执行驱动中的一个方法
     * @param string $fun
     * @param array $par
     * @return mixed
     */
    public function __call(string $fun, array $par = []) {
        return call_user_func_array([$this->driver, $fun], $par);
    }

}
