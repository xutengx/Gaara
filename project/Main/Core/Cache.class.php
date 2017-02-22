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
        if (CACHEDRIVER === 'redis')
            $this->Drivers['redis'] = new \Main\Core\Cache\Driver\Redis(
                    ['host' =>REDISHOST]
            );
        else
            $this->Drivers['file'] = new \Main\Core\Cache\Driver\File();
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
    public function call($obj, $func, $cacheTime=true){
        $cacheTime = is_numeric($cacheTime) ? (int)$cacheTime : $this->cacheLimitTime;
        $pars = func_get_args();
        unset($pars[0]);
        unset($pars[1]);
        unset($pars[2]);
        $par = array_values($pars);
        $key = $this->makeCacheDir($obj, $func, $par);
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
            $re = $v->callset($key,$echo, $return, $cacheTime);
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
        $key = str_replace('/#default/', '', $key);
        foreach($this->Drivers as $v){
            $v->clear($key);
        }
        return true;
    }
    public function set($key=true, $velue, $cacheTime=false){
        $cacheTime = is_numeric($cacheTime) ? (int)$cacheTime : $this->cacheLimitTime;
        $key        = ($key === true) ? $this->autoKey() : $key;
        foreach($this->Drivers as $v){
            $re = $v->set($key, $velue, $cacheTime);
            if($re)
                return true;
        }
        return false;
    }
    public function get($key=true, $callback=false, $cacheTime=false){
        $cacheTime = is_numeric($cacheTime) ? (int)$cacheTime : $this->cacheLimitTime;
        $key        = ($key === true) ? $this->autoKey() : $key;
        foreach($this->Drivers as $v){
            $re = $v->get($key);
            if($re['code'] === 200)
                return $re['data'];
        }
        if($callback !== false){
            if(is_string($callback) || is_numeric($callback) || is_array($callback))
                $re = $callback;
            else $re = call_user_func($callback);
            if($this->set($key, $re, $cacheTime))
                return $re;
        }
        return false;
    }

    public function rm($key){
        $key = ($key === true) ? $this->autoKey() : $key;
        foreach($this->Drivers as $v){
            $re = $v->rm($key);
            if($re)
                return true;
        }
        return false;
    }
    // 在"同一调用方法"中,不应使用多于一个的自动命名
    // 由执行缓存方法的环境,生成缓存 key = 调用类\-\调用方法
    private function autoKey(){
        $class = '';                // 缓存调用类
        $func = '';                 // 缓存调用方法
        $debug = debug_backtrace();
        // 自动生成 缓存键
//        if($debug[1]['args'][0] === true){
//            // 数据来源是闭包函数
//            if($debug[1]['args'][1] instanceof \Closure){
                foreach($debug as $v){
                    if(!($v['object'] instanceof \Main\Core\Cache)){
                        $class = get_class($v['object']);
                        $func = $v['function'];
                        break;
                    }
//                }
//            }
        }
        return $class.'\-\\'.$func;
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
    public function __get($par){
        if(isset($this->Drivers[strtolower($par)]))
            return $this->Drivers[strtolower($par)];
        else throw new Exception('需要的缓存驱动不存在!');
    }
    // 使用Driver中额外支持的方法(多层__call调用)
    public function __call($fun, $par=array()){
        $parstr = '';
        if($par !== NULL){
            $par = array_values($par);
            for( $i = 0 ; $i < count($par) ; $i++ )
                $parstr .= ',$par['.$i.']';
            $parstr = ltrim($parstr, ',');
        }
        foreach ($this->Drivers as $v) {
            $eval = '$v->$fun('.$parstr.');';
            $re = eval('return '.$eval);
            if($re['code'] === 200)
                return $re['data'];
        }
        throw new Exception('缓存驱动无法执行"'.$fun.'"方法');
    }
}
