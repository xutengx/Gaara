<?php
namespace Main;
defined('IN_SYS')||exit('ACC Denied');
class Cache {
    // 缓存目录
    private  $cacheRoot        ;
    // 默认缓存更新时间秒数，0为不缓存
    private  $cacheLimitTime   = 30;
    // 缓存文件名
    private  $cacheFileName    = "";
    // 缓存扩展名
    private  $cacheFileExt     = "html";

    private static $ins = null;

    /*
     * 构造函数
     * int $time 缓存更新时间
     */
    final protected function __construct($time = false){
        $this->cacheRoot = ROOT.'data/Cache/' . APP . 'App';
        if((int)$time) $this->cacheLimitTime = $time;
    }
    public static function getins($time){
        if((self::$ins instanceof self) || (self::$ins = new self($time))) return self::$ins;
    }
    // 页面缓存
    public function htmlCacheCheck($contr, $key='default', $cacheTime=false){
        $key = $key  ? $key : 'default';
        $this->cacheFileName = $this->getCacheContrName($contr, $key);
        ob_start();
        if( file_exists( $this->cacheFileName ) && $this->cacheLimitTime !== 0 ) {
            $cTime = $this->getFileCreateTime( $this->cacheFileName );
            $cacheTime = $cacheTime ? (int)$cacheTime : $this->cacheLimitTime;
            if( ($cTime + $cacheTime + 6) > time() ) {      // 文件创建时间会提前6,7秒,未知原因
                echo file_get_contents( $this->cacheFileName );
                ob_end_flush();
                exit;
            }
        }
    }

    // 正常输出 页面缓存 并写入文件
    public function htmlCacheOut(){
        $cacheContent = ob_get_contents();
        ob_end_flush();
        $this->saveFile( $this->cacheFileName, $cacheContent );
    }
    // 数据缓存
    public function dataCacheCheck($module,$functionName, $str='default',$cacheTime=false){
        $this->cacheFileName = $this->getCacheModuleName($module, $functionName, $str);
        ob_start();
        if( file_exists( $this->cacheFileName ) && $this->cacheLimitTime !== 0 ) {
            $cTime = $this->getFileCreateTime( $this->cacheFileName );
            $cacheTime = $cacheTime ? (int)$cacheTime  : $this->cacheLimitTime;
            if( ($cTime + $cacheTime + 6) > time() ) {
                echo file_get_contents( $this->cacheFileName );
                ob_end_flush();
                exit;
            }
        }
    }
    // 正常输出 数据缓存 并写入文件
    public function dataCacheOut(){
        $cacheContent = ob_get_contents();
        ob_end_flush();
        $this->saveFile( $this->cacheFileName, $cacheContent );
    }
    // 清除缓存, 可指定
    public function clearCache($App, $Contr, $Func){
        $dirName = ROOT.'data/Cache/';
        $dirName .= $App ? $App.'App/' : '';
        $dirName .= $Contr ? $Contr.'Contr/' : '';
        $dirName .= $Func ? $Func : '';
        $this->del_DirAndFile($dirName);
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
    // 根据当前动态文件生成缓存文件名  页面缓存用
    private function getCacheContrName($contr,  $key='default') {
        return  $this->cacheRoot .'/'.$contr.'Contr/display/'.$key.'.'.$this->cacheFileExt;
    }
    // 根据当前动态文件生成缓存文件名 data缓存用
    private function getCacheModuleName($contr, $functionName,  $key='default') {
        return  $this->cacheRoot .'/'.$contr.'Contr/'.$functionName.'/'.$key.'.'.$this->cacheFileExt;
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
        if( $fp = fopen( $fileName, "w" ) ) {
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
