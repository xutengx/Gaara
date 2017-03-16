<?php
namespace Main\Core\Cache\Driver;
use Main\Core\Cache\DriverInterface;
use Main\Core\Exception;

defined('IN_SYS')||exit('ACC Denied');
class Redis implements DriverInterface {
    // 键名前缀
    public $prefix = 'RF1:';
    private $handler = null;

    public function __construct($options=array()){
        $this->prefix = isset($options['prefix']) ? $options['prefix'] : $this->prefix;
        if ( !extension_loaded('redis') )
            throw new Exception('当前php,尚未安装redis拓展!');
        $this->handler = new \redis();
        $connect = (CLI===true) ? 'pconnect' : 'connect';
        $this->handler->$connect(
            isset($options['host']) ? $options['host'] : '127.0.0.1',
            isset($options['port']) ? $options['port'] : 6379
        );
    }

    public function get($key){
        if (!$this->handler->exists($this->prefix . $key))
            return array(
                'code' => 0
            );
        $value = $this->handler->get($this->prefix.$key);
        $data  = json_decode( $value, true );
        $data  = ($data === null) ? $value : $data;
        return array(
            'code'=> 200,
            'data'=>$data
        );
    }
    public function set($key, $value, $cacheTime=false){
        $value  =  (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        try{
            if(is_int($cacheTime)) {
                $value = $this->handler->setex($this->prefix.$key, $cacheTime, $value);
            }else{
                $value = $this->handler->set($this->prefix.$key,  $value);
            }
        }catch(\RedisException $e){
            return false;
        }
        return true;
    }
    public function rm($key){
        try{
            $this->handler->delete($this->prefix.$key);
        }catch(\RedisException $e){
            return false;
        }
        return true;
    }
//    public function clear($key){
//        $arr = $this->handler->keys($this->prefix.$key.'*');
//        foreach($arr as $v){
//            $this->handler->delete($v);
//        }
//    }
    // 以scan替代keys, 解决大数据时redis堵塞的问题, 但是存在数据不准确(清除数据不完整)的情况
    public function clear($key){
        $it = \NULL; /* Initialize our iterator to NULL */
        while($arr_keys = $this->handler->scan($it, $this->prefix.$key.'*', 10000)) {
            foreach($arr_keys as $str_key) {
                $this->handler->delete($str_key);
            }
        }
    }
    public function callget($key,$cacheTime){
        $echo = ($key.'e');
        $return = ($key.'r');
        
        $echo_content = $this->get($echo);
        $return_content = $this->get($return);
        if($echo_content['code'] === 200 && $return_content['code'] === 200){
            echo $echo_content['data'];
            return array(
                'code' => 200,
                'data' => $return_content['data']
            );
        }
        return false;

    }
    public function callset($cachedir, $echo='',$return, $cacheTime){
        $echo_key = ($cachedir.'e');
        $return_key = ($cachedir.'r');
        
        $a = $this->set($echo_key, $echo, $cacheTime);
        $b = $this->set($return_key, $return, $cacheTime);
        if ($a && $b) {
            return array(
                'code' => 200,
                'data' => $return
            );
        }
    }
    
    public function __call($func, $pars){
        if(method_exists($this->handler, $func)){
            $res = call_user_func_array(array($this->handler, $func), $pars);
            return array(
                'code'=> 200,
                'data'=> $res
            );
        }
    }
}