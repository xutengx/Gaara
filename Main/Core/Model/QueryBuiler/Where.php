<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

use Closure;

trait Where {
    
    /**
     * 加入一个不做处理的条件
     * @param string $str
     */
    public function whereRaw(string $str){
        
    }
    
    /**
     * 闭包
     * @param Closure $callback
     */
    public function whereClosure(Closure $callback){
       $res = $callback($this->getSelf());

       // todo
    }
    
    /**
     * 相等
     * @param string $field
     * @param string $Symbol
     * @param string $value
     */
    public function whereEqual(string $field, string $Symbol, string $value){
        
    }
    
    public function whereArr(){
        
    }

    public function orWhere(){
        
    }
    
 
    public function whereExists(){
        
    }
    
 
    public function whereBetween(){
        
    }
    
 
    public function whereNotBetween(){
        
    }
    
 
    public function whereIn(){
        
    }
    
 
    public function whereNotIn(){
        
    }
    
 
    public function whereNull(){
        
    }
}