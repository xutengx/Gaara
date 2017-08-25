<?php

namespace Main\Core;
defined('IN_SYS') || exit('ACC Denied');

use \Main\Core\Cache\Driver;

class Cache {

    // 缓存驱动池
    private $Drivers = [];
    // 默认缓存更新时间秒数
    public $cacheLimitTime = 300;
    // 缓存唯一标识(可用当前用户id)
    private $key = null;

    final public function __construct($time = 300, $key = null) {
        $this->cacheLimitTime = (int) $time;
        $this->key = $key;

        $conf = obj(Conf::class)->cache;

        if ($conf['driver'] === 'redis')
            $this->Drivers['redis'] = new Driver\Redis($conf[$conf['driver']]);
        elseif ($conf['driver'] === 'file')
            $this->Drivers['file'] = new Driver\File($conf[$conf['driver']]);
    }

    /**
     * 方法缓存 兼容 return 与 打印输出
     * @param object  $obj 执行对象
     * @param string  $func 执行方法
     * @param bool|true $cacheTime 缓存过期时间
     * @param $par 非限定参数 
     *
     * @return mixed
     */
    public function call($obj, $func, $cacheTime = true) {
        $cacheTime = is_numeric($cacheTime) ? (int) $cacheTime : $this->cacheLimitTime;
        $pars = func_get_args();
        unset($pars[0]);
        unset($pars[1]);
        unset($pars[2]);
        $par = array_values($pars);
        $key = $this->autoKey($obj, $func, $par);
        foreach ($this->Drivers as $v) {
            $re = $v->callget($key, $cacheTime);
            if ($re['code'] === 200)
                return $re['data'];
        }
        $return = $this->runFunc($obj, $func, $par);
        foreach ($this->Drivers as $v) {
            $re = $v->callset($key, $return, $cacheTime);
            if ($re['code'] === 200)
                return $re['data'];
        }
        return $return;
    }

    /**
     * 不缓存的缓存方法, 方便调试
     * @param object  $obj 执行对象
     * @param string  $func 执行方法
     * @param bool|true $cacheTime 缓存过期时间
     * @param $par 非限定参数 
     *
     * @return mixed
     */
    public function dcall($obj, $func, $cacheTime = true) {
        $cacheTime = is_numeric($cacheTime) ? (int) $cacheTime : $this->cacheLimitTime;
        $pars = func_get_args();
        unset($pars[0]);
        unset($pars[1]);
        unset($pars[2]);
        $par = array_values($pars);
        $key = $this->autoKey($obj, $func, $par);

        $return = $this->runFunc($obj, $func, $par);
        foreach ($this->Drivers as $v) {
            $re = $v->callset($key, $return, $cacheTime);
            if ($re['code'] === 200)
                return $re['data'];
        }
        return $return;
    }

    public function clear($obj, $func) {
        $pars = func_get_args();
        unset($pars[0]);
        unset($pars[1]);
        $par = array_values($pars);
        $key = $this->autoKey($obj, $func, $par);
        foreach ($this->Drivers as $v) {
            $v->clear($key);
        }
        return true;
    }

    public function set($key = true, $velue, $cacheTime = false) {
        $cacheTime = is_numeric($cacheTime) ? (int) $cacheTime : $this->cacheLimitTime;
        $key = ($key === true) ? $this->autoKey() : $key;
        foreach ($this->Drivers as $v) {
            $re = $v->set($key, $velue, $cacheTime);
            if ($re)
                return true;
        }
        return false;
    }

    public function get($key = true, $callback = false, $cacheTime = false) {
        if ($key instanceof \Closure) {
            $cacheTime = $callback;
            $callback = $key;
            $key = true;
        }
        $cacheTime = is_numeric($cacheTime) ? (int) $cacheTime : $this->cacheLimitTime;
        $key = ($key === true) ? $this->autoKey($callback) : $key;

        foreach ($this->Drivers as $v) {
            $re = $v->get($key);
            if ($re['code'] === 200)
                return $re['data'];
        }

        if ($callback !== false) {
            if ($callback instanceof \Closure) {
                $data = call_user_func($callback);
            } else {
                $data = $callback;
            }
            if ($this->set($key, $data, $cacheTime))
                return $data;
        }
        return null;
    }

    /**
     * 不缓存的缓存方法, 方便调试
     * @param type $key
     * @param type $callback
     * @param type $cacheTime
     * @return boolean|\Closure
     */
    public function dget($key = true, $callback = false, $cacheTime = false) {
        if ($key instanceof \Closure) {
            $cacheTime = $callback;
            $callback = $key;
            $key = true;
        }
        $cacheTime = is_numeric($cacheTime) ? (int) $cacheTime : $this->cacheLimitTime;
        $key = ($key === true) ? $this->autoKey($callback) : $key;
        if ($callback !== false) {
            if ($callback instanceof \Closure) {
                $data = call_user_func($callback);
            } else {
                $data = $callback;
            }
            if ($this->set($key, $data, $cacheTime))
                return $data;
        }
        return null;
    }

    public function rm($key = true) {
        $key = ($key === true) ? $this->autoKey() : $key;
        foreach ($this->Drivers as $v) {
            $re = $v->rm($key);
            if ($re)
                return true;
        }
        return false;
    }

    // 在"同一调用方法"中,不应使用多于一个的自动命名
    // 由执行缓存方法的环境,生成缓存 key = 调用类\-\调用方法
    private function autoKey($class = false, $func = '', $params = []) {
        if ($class === false) {
            $debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
            foreach ($debug as $v) {
                if ($v['class'] === 'Main\Core\Cache' && ( $v['function'] === 'get' || $v['function'] === 'dget')) {
                    $func = 'Closure_' . $v['line'];
                } elseif ($v['class'] !== 'Main\Core\Cache') {
                    $class = $v['class'];
                    break;
                }
            }
        } elseif ($class instanceof \Closure) {
            $class = $this->analysisClosure($class);
            $debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
            foreach ($debug as $v) {
                if ($v['class'] === 'Main\Core\Cache' && ( $v['function'] === 'get' || $v['function'] === 'dget')) {
                    $func = 'Closure_' . $v['line'];
                } elseif ($v['class'] !== 'Main\Core\Cache') {
                    $class .= '/parent/' . $v['class'];
                    break;
                }
            }
        } else
            $class = is_object($class) ? get_class($class) : $class;
        return $this->makeKey($class, $func, $params);
    }

    /**
     * 
     * 
     */
    private function makeKey($classname = '', $funcname = '', $params = []) {
        $key = '';                   // default
        if (!empty($params)) {
            foreach ($params as $v) {
                if (is_object($v))
                    throw new Exception('以此种缓存方法, 不支持以对象作为参数, 因为没有一致的方法判断对象是相等的. ');
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

    // 反射执行非公开方法方法
    private function runFunc($obj, $func, $args) {
        $reflectionClass = new \ReflectionClass($obj);
        $method = $reflectionClass->getMethod($func);
        $closure = $method->getClosure($obj);
        return $closure(...$args);
    }

    /**
     * 返回闭包函数的this指向的类名
     * @param \Closure $closure
     * @return string
     */
    private function analysisClosure(\Closure $closure): string {
        ob_start();
        var_dump($closure);
        $info = ob_get_contents();
        ob_end_clean();
        $info = str_replace([" ", "　", "\t", "\n", "\r"], '', $info);
        $class = '';
        \preg_replace_callback("/{\[\"this\"\]=>object\((.*?)\)\#/is", function($matches) use (&$class) {
            $class = $matches[1];
        }, $info);
        return $class;
    }

    public function __get($par) {
        if (isset($this->Drivers[strtolower($par)]))
            return $this->Drivers[strtolower($par)];
        else
            throw new Exception('需要的缓存驱动不存在!');
    }

    // 使用Driver中额外支持的方法
    public function __call($fun, $par = array()) {
        foreach ($this->Drivers as $v) {
            $re = call_user_func_array([$v, $fun], $par);
            if ($re['code'] === 200)
                return $re['data'];
        }
        throw new Exception('缓存驱动无法执行"' . $fun . '"方法 or 方法返回不正常');
    }
}
