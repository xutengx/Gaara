<?php

declare(strict_types = 1);
namespace Main\Core;
defined('IN_SYS') || exit('ACC Denied');

use \Main\Core\Pipeline\Traits;

/**
 * 管道模式
 */
class Pipeline {
    
    // 设置管道流程相关
    use Traits\SetPipes;
    
    // 流程 类名以@分割构造参数
    private $pipes = [];
    
    // 默认闭包
    private $defaultClosure = null;
    
    // 管道执行的方法
    private $func = 'implement';
    
    // 设置匿名回调函数
    public function setDefaultClosure(\Closure $callback){
        $this->defaultClosure = $callback;
    }

    // 执行
    public function then() {
        call_user_func(array_reduce(array_reverse($this->pipes), $this->getSlice(), $this->defaultClosure()));
    }
    
    // 匿名回调函数 ( 控制器执行 )
    private function defaultClosure() {
        return function () {
            return call_user_func($this->defaultClosure);
        };
    }

    // 管道堆
    private function getSlice() {
        return function ($stack, $pipe) {
            return function () use ($stack, $pipe) {
                return $this->getObj($pipe)->{$this->func}($stack);
            };
        };
    }
    /**
     * new 一个对象. eg App\Min\tt@1@44  ---> new App\Min\tt (1, 44)
     * @param type $class
     */
    private function getObj($class){
        $arr = explode('@', $class);
        $middlewareObj = array_shift($arr);
        return new $middlewareObj( ...$arr);
    }
}
