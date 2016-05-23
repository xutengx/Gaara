<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
abstract class Controller{
    /**
     * trait 构造函数队列
     * @var array
     */
    protected $__construct = [];
    // 引入cache类
    protected $phpcache = NULL;

    /**
     * @param string $func 将要执行的方法名
     */
    final public function __construct($func=''){
        $this->phpcache = obj('\Main\Core\Cache',true,30);
        $this->traitConstruct();
        $this->construct($func);
    }
    /**
     * trait 构造方法兼容器,执行别名的__construct
     * @return mixed
     */
    final public function traitConstruct(){
        foreach($this->__construct as $v){
            if(method_exists($this, $func = $v.'Construct'))
                $this->$func();
        }
    }
    protected function construct(){
    }
    // 一般入口
    abstract public function indexDo();

    /**
     * @param string $func 呼叫的protected方法
     * @param array  $agrs 参数数组
     *
     * @return mixed
     */
    final public function runProtectedFunction( $func='', array $agrs = array() ){
        return call_user_func_array( array( $this, $func ), $agrs );
    }
}