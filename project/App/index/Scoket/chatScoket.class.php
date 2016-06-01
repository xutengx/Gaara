<?php
namespace App\index\Scoket;
use \Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class chatScoket extends Core\Workerman{
    protected function returnData($connection, $data){
//        $a = var_export($data);
        $connection->send('hello ' . $data);
    }
}