<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/21 0021
 * Time: 17:12
 */
namespace Main;
defined('IN_SYS')||exit('ACC Denied');
class log{
    //const LOGDIR    = ROOT.'data/log/';                //文件路径
    const LOGDIR    = 'data/dblog/';                //文件路径
    const LOGFILE   = 'curr.log';     	               //文件名称
    public static function write($cont){
        $cont.="\r\n";						               //添加换行符
        $con = date('Y-m-d H:i:s', time()).' '.$cont;
        $log = self::createlog();
        $fh = fopen($log, 'ab');
        fwrite($fh, $con);
        fclose($fh);
    }
    // 备份日志
    public static function bak(){
        $log = self::LOGDIR . self::LOGFILE;
        $bak = self::LOGDIR . date('ymd') . '_' . time() .'_' . mt_rand(10000,99999).'.bak';
        return rename($log,$bak);
    }
    // 读取并判断日志的大小
    public static function createlog(){
        $log = self::LOGDIR . self::LOGFILE;
        if(!file_exists($log)){
            if(is_dir(dirname($log)) || self::_mkdir(dirname($log))) touch($log);
            return $log;
        }
        // 清除缓存
        clearstatcache();
        if((filesize($log) <= 1024*1024) || !self::bak() || touch($log)) return $log;
    }
    private static function _mkdir($dir, $mode = 0777 ){
        if(is_dir(dirname($dir)) || self::_mkdir(dirname($dir))) return mkdir($dir, $mode);
    }
}
