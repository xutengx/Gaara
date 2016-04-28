<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Base{
    protected static $ins  = null;
    public static function getins(){
        if(static::$ins instanceof static || (static::$ins = new static)) return static::$ins;
    }
    // 模块间重定向
    final protected function headerTo($msg='跳转中!',$where,$jump=true){
        $where = IN_SYS.'?'.PATH.'='.$where;
        $jump ? obj('template')->jumpTo($msg, $where) : header('location:'.$where);
    }

    /**
     * @param $func 呼叫的protected方法
     * @param $agrs 参数数组
     * @return mixed
     */
    final public function runProtectedFunction($func,array $agrs = array()){
        return call_user_func_array(array($this, $func), $agrs);
    }
}