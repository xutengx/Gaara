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
    final public function getThis(){
        return get_class($this);
    }
//    final public function cacheCall($obj, $func, $cacheTime=false){
//        $pars = func_get_args();
//        $parstr = '';
//        unset($pars[0]);
//        unset($pars[1]);
//        unset($pars[2]);
//        $par = array_values($pars);
//        for($i = 0 ; $i < count($par) ; $i++){
//            $parstr .= ',$par['.$i.']';
//        }
//        $parstr = ltrim($parstr, ',');
//
//        $code = 'return $obj->{$func}('.$parstr.');';
//        if($ss = obj('cache')->caheCall($obj->getThis(),$par,$cacheTime) )
//            return $ss;
//        $bool = eval($code);
//        return obj('cache')->funcEnd($bool);
//    }
}