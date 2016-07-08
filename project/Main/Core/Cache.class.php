<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Cache {
    // 缓存驱动池
    private $Drivers = [];
    // 默认缓存更新时间秒数
    public $cacheLimitTime   = 30;

    final public function __construct($time = 30){
        $this->cacheLimitTime = (int)$time;
        $this->Drivers[] = new \Main\Core\Cache\Driver\Redis();
        $this->Drivers[] = new \Main\Core\Cache\Driver\File();
    }

    /**
     * 方法缓存 兼容 return 与 打印输出
     * @param object  $obj 执行对象
     * @param string  $func 执行方法
     * @param bool|true $cacheTime 缓存过期时间
     *
     * @return mixed
     */
    public function call($obj, $func, $cacheTime=true){
        $cacheTime = is_numeric($cacheTime) ? (int)$cacheTime : $this->cacheLimitTime;
        $pars = func_get_args();
        unset($pars[0]);
        unset($pars[1]);
        unset($pars[2]);
        $par = array_values($pars);
        $key = $this->makeCacheDir($obj, $func, $par);
//        var_dump($key);exit;

        foreach($this->Drivers as $v){
            $re = $v->callget($key, $cacheTime);
            if($re['code'] === 200)
                return $re['data'];
        }
        ob_start();
        $return = $this->runFunc($obj, $func, $par);
        $echo = ob_get_contents();
        ob_end_flush();
        foreach($this->Drivers as $v){
            $re = $v->callset($key,$echo, $return);
            if($re['code'] === 200)
                return $re['data'];
        }
        return $return;
    }
    public function clear($obj, $func){
        $pars = func_get_args();
        unset($pars[0]);
        unset($pars[1]);
        $par = array_values($pars);
        $key = $this->makeCacheDir($obj, $func, $par);
        foreach($this->Drivers as $v){
            $v->clear($key);
        }
        return true;
    }
    public function set($key, $velue, $cacheTime=false){
        $cacheTime = is_numeric($cacheTime) ? (int)$cacheTime : $this->cacheLimitTime;
        foreach($this->Drivers as $v){
            $re = $v->set($key, $velue, $cacheTime);
            if($re)
                return true;
        }
        return false;
    }
    public function get($key, $callback=false, $cacheTime=false){
        $cacheTime = is_numeric($cacheTime) ? (int)$cacheTime : $this->cacheLimitTime;
        foreach($this->Drivers as $v){
            $re = $v->get($key);
            if($re['code'] === 200)
                return $re['data'];
        }
        if($callback !== false){
            $re = call_user_func($callback);
            if($this->set($key, $re, $cacheTime))
                return $re;
        }
        return false;
    }
    public function rm($key){
        foreach($this->Drivers as $v){
            $re = $v->rm($key);
            if($re)
                return true;
        }
        return false;
    }

    /**
     * @param object $obj 执行对象
     * @param string $func 执行方法
     * @param array $keyArray 参数数组
     *
     * @return string 缓存文件地址
     */
    private function makeCacheDir($obj, $func=false, array $keyArray = array()){
        $funDir = $func ? get_class($obj).'/'.$func.'/' : get_class($obj);
        $dir = str_replace('\\','/',$funDir);
        $key = '';
        if(!empty($keyArray) ){
            foreach($keyArray as $k=>$v){
                if($v === true) $key .= '_boolean-true';
                elseif($v === false) $key .= '_boolean-false';
                else $key .= '_'.gettype($v).'-'.(is_array($v)?serialize($v):$v);
            }
            $key =md5($key);
        }
        else $key .= '#default';
        return  $func ? $dir.$key.'/' : $dir.'/' ;
    }
    // 执行方法
    private function runFunc($obj, $func, $args){
        if (method_exists($obj, 'runProtectedFunction')) return $obj->runProtectedFunction($func, $args);
        else return call_user_func_array(array($obj, $func), $args);
    }
}
