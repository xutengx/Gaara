<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Log{
    const LOGDIR    = 'data/dblog/';                //文件路径
    const LOGFILE   = 'curr.log'; 		               //文件名称
    public function write($content){
        $cont ="\r\n";						               //添加换行符
        $con = date('Y-m-d H:i:s', time()).$cont.$content.$cont.$cont;
        $log = $this->createlog();
        $fh = fopen($log, 'ab');
        fwrite($fh, $con);
        fclose($fh);
    }
    // 备份日志
    private function bak(){
        $log = $this->LOGDIR . $this->LOGFILE;
        $bak = $this->LOGDIR . date('ymd') . '_' . time() .'_' . mt_rand(10000,99999).'.bak';
        return rename($log,$bak);
    }
    // 读取并判断日志的大小
    private function createlog(){
        $log = $this->LOGDIR . $this->LOGFILE;
        if(!file_exists($log)){
            if(is_dir(dirname($log)) || $this->_mkdir(dirname($log))) touch($log);
            return $log;
        }
        // 清除缓存
        clearstatcache();
        if((filesize($log) <= 1024*1024) || !$this->bak() || touch($log)) return $log;
    }
    private function _mkdir($dir, $mode = 0777 ){
        if(is_dir(dirname($dir)) || $this->_mkdir(dirname($dir))) return mkdir($dir, $mode);
    }
}
