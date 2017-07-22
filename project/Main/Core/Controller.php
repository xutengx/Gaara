<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
abstract class Controller{
    final public function runProtectedFunction( $func='', array $agrs = array() ){
        return call_user_func_array( array( $this, $func ), $agrs );
    }
}