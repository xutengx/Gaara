<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Cache {
    // 缓存目录
    private  $cacheRoot        ;
    // 默认缓存更新时间秒数，0为不缓存
    private  $cacheLimitTime   = 30;
    // 缓存文件名
    private  $cacheFileName    = "";
    // 方法缓存文件名
    private  $cacheFuncFileName    = "";
    // 是否缓存
    private  $cacheAble    = false;
    // 缓存扩展名
    private  $cacheFileExt     = "html";
    final public function __construct($time = 30){
        $this->cacheRoot = ROOT.'data/Cache/';
        $this->cacheLimitTime = (int)$time;
    }
    final public function __destruct(){
        if($this->cacheAble)
            $this->cacheEnd();
    }
    // 缓存开启
    public function cacheBegin($app, $contr, $methor,$keyArray,$cacheTime=false){
        $this->cacheFileName = $this->makeCaheName($app, $contr, $methor, $keyArray);
        ob_start();
        if( file_exists( $this->cacheFileName ) && $this->cacheLimitTime !== 0 ) {
            $cTime = $this->getFileCreateTime( $this->cacheFileName );
            $cacheTime = $cacheTime ? (int)$cacheTime  : $this->cacheLimitTime;
            if( ($cTime + $cacheTime ) > time() ) {
                $this->cacheAble = false;
                echo file_get_contents( $this->cacheFileName );
                ob_end_flush();
                exit;
            }
        }
        $this->cacheAble = true;
    }
    // 缓存结束 正常输出 页面缓存 并写入文件
    private function cacheEnd(){
        $cacheContent = ob_get_contents();
        ob_end_flush();
        if($this->cacheLimitTime !== 0)
            $this->saveFile( $this->cacheFileName, $cacheContent );
    }
    // 缓存方法
    public function cacheCall($app, $class, $func, $keyArray, $cacheTime = false){
        $this->cacheFuncFileName = $this->makeCaheNameForCall($app, $class, $func, $keyArray);
        if (file_exists($this->cacheFuncFileName) && $this->cacheLimitTime !== 0) {
            $cTime = $this->getFileCreateTime($this->cacheFuncFileName);
            $cacheTime = $cacheTime ? (int)$cacheTime : $this->cacheLimitTime;
            if (($cTime + $cacheTime) > time()) {
                return unserialize((file_get_contents($this->cacheFuncFileName)));
            }
        }
        return false;
    }
    public function funcEnd($bool){
        if($this->cacheLimitTime !== 0)
            $this->saveFile( $this->cacheFuncFileName,  (serialize($bool)) );
        return $bool;
    }
    // 清除缓存, 可指定
    public function cacheClear($App='', $Contr='', $Func=''){
        $dirName = ROOT.'data/Cache/';
        $dirName .= $App ? $App.'App/' : '';
        $dirName .= $Contr ? $Contr : '';
        $dirName .= $Func ? $Func : '';
        $this->del_DirAndFile($dirName);
    }
    // 根据当前动态文件生成缓存文件名 data缓存用
    private function makeCaheNameForCall($app, $class, $func, $keyArray ) {
        $key = '';
        if(is_array($keyArray) ){
            foreach($keyArray as $k=>$v){
                $key .= $key ? '_'.$v : $v;
            }
        }else if($keyArray === '')
            $key .= 'default';
        else $key .= $keyArray;
        return  $this->cacheRoot .$app.'App/'.$class.'/'.$func.'/'.$key.'.'.$this->cacheFileExt;
    }
    // 根据当前动态文件生成缓存文件名 data缓存用
    private function makeCaheName($app, $contr, $methor,  $keyArray ) {
        $key = '';
        if(is_array($keyArray)){
            foreach($keyArray as $k=>$v){
                $key .= $key ? '_'.$v : $v;
            }
        }else if($keyArray === '')
            $key .= 'default';
        else $key .= $keyArray;
        return  $this->cacheRoot .$app.'App/'.$contr.'Contr/'.$methor.'/'.$key.'.'.$this->cacheFileExt;
    }
    // 递归删除 目录(绝对路径)下的所有文件,不包括自身
    private function del_DirAndFile($dirName){
        if (is_dir($dirName) && $dir_arr = scandir($dirName)){
            foreach($dir_arr as $k=>$v){
                if($v == '.' || $v == '..'){}
                else{
                    if(is_dir($dirName.'/'.$v)){
                        $this->del_DirAndFile($dirName.'/'.$v);
                        rmdir($dirName.'/'.$v);
                    }else unlink($dirName.'/'.$v);
                }
            }
        }
    }
    /*
     * 缓存文件建立时间
     * string $fileName   缓存文件名（绝对路径）
     * 返回：文件生成时间秒数，文件不存在返回0
     */
    private function getFileCreateTime( $fileName ) {
        if( ! trim($fileName) ) return 0;
        if( file_exists( $fileName ) ) {
            return (int)filemtime($fileName);
        }else return 0;
    }

    /*
     * 保存文件
     * string $fileName  文件名（含相对路径）
     * string $text      文件内容
     * 返回：成功返回ture，失败返回false
     */
    private function saveFile($fileName, $text) {
        if( ! $fileName || ! $text ) return false;
        if(!file_exists($fileName)){
            if(is_dir(dirname($fileName)) || $this->_mkdir(dirname($fileName))) touch($fileName);
        }
        if( $fp = fopen( $fileName, "wb" ) ) {
            flock($fp, LOCK_EX | LOCK_NB);
            if(fwrite( $fp, $text ) ) {
                fclose($fp);
                return true;
            }else {
                fclose($fp);
                return false;
            }
        }
        return false;
    }
    private function _mkdir($dir, $mode = 0777 ){
        if(is_dir(dirname($dir)) || $this->_mkdir(dirname($dir))) return mkdir($dir, $mode);
    }
}
