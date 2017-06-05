<?php
namespace App\development\Dev;
defined('IN_SYS') || exit('ACC Denied');
// 异步消息通知
class asyncDev {
    public function asynExe() {
        \asynExe();
    }
    public function remote($ay) {
        return \remote($ay);
    }
    
    // 
    public function write_something_into_log(){
        
    }
}
