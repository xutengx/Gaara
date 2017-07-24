<?php
namespace Main\Core\Session\Driver;

defined('IN_SYS') || exit('ACC Denied');

class Redis {
    public function __construct($options = array()) {
        $host = ( isset($options['host']) && !is_null($options['host']) ) ? $options['host'] : '127.0.0.1';
        $port = ( isset($options['port']) && !is_null($options['port']) ) ? $options['port'] : 6379 ;
        $passwd = ( isset($options['passwd']) && !is_null($options['passwd']) ) ? '?auth='.$options['passwd'] : '' ;
        
        ini_set('session.save_handler', 'redis');
        ini_set('session.save_path', 'tcp://'.$host.':'.$port.$passwd);
    }
}
