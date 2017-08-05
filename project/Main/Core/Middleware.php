<?php

namespace Main\Core;

defined('IN_SYS') || exit('ACC Denied');

/**
 * 中间件父类
 */
abstract class Middleware {
    protected $except = []; 
    
    final public function __construct() {
        ;
    }
    
    final public function __invoke(Request $request){
        if(!in_array(Route::getAlias(), $this->except)){
            $this->handle($request);
        }
    }
    abstract public function handle(Request $request);
    
}
