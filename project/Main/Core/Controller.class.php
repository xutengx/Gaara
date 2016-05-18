<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
abstract class Controller{
    // 一般入口
    public function indexDo(){}
    /**
     * 重定向到指定路由
     * @param string        $where 指定路由,如:index/index/indexDo/
     * @param string|false  $msg   跳转中间页显示信息|不使用中间页
     * @param array         $pars  参数数组
     */
    final protected function headerTo($where='', $msg = false, array $pars = array()){
        $str = '';
        foreach($pars as $k=>$v){
            $str .= $k.'/'.$v.'/';
        }
        $where = IN_SYS.'?'.PATH.'='.$where.$str;
        ( $msg!==false ) ? obj('template')->jumpTo($msg, $where) : header('location:'.$where);
    }
    /**
     * @param string $func 呼叫的protected方法
     * @param array  $agrs 参数数组
     *
     * @return mixed
     */
     public function runProtectedFunction( $func='', array $agrs = array() ){
        return call_user_func_array( array( $this, $func ), $agrs );
    }
}