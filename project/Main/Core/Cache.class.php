<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Cache {
    // 缓存目录
    private  $cacheRoot        ;
    // 默认缓存更新时间秒数，0为不缓存
    private  $cacheLimitTime   = 30;
    // 缓存扩展名
    private  $cacheFileExt     = "html";
    final public function __construct($time = 30){
        $this->cacheRoot = ROOT.'data/Cache/';
        $this->cacheLimitTime = (int)$time;
    }
    // 获取缓存
    private function getCache($cachedir, $cacheTime ){
        $echo   = $cachedir . 'echo.' . $this->cacheFileExt;
        $return = $cachedir . 'return.' . $this->cacheFileExt;

        if( file_exists($echo) || file_exists($return) && $this->cacheLimitTime !== 0){
            $cTime = ( $t = $this->getFileCreateTime($echo) ) ? $t : $this->getFileCreateTime($return );
            $cacheTime = $cacheTime ? (int)$cacheTime  : $this->cacheLimitTime;
            if( ($cTime + $cacheTime ) > time() ) {
                $data = NULL;
                if(file_exists($echo)) echo file_get_contents($echo);
                if(file_exists($return)) $data = unserialize(file_get_contents($return));
                return array('status'=>true,'data'=>$data);
            }
        }
        return false;
    }
    // return 对应的缓存文件夹名
    private function makeCacheDir($obj, $func, $keyArray){
        $dir = str_replace('\\','/',get_class($obj).'/'.$func.'/');
        $key = '';
        if(is_array($keyArray) )
            foreach($keyArray as $k=>$v){
                switch($v){
                    case true:
                        $v = '#true';
                        break;
                    case false:
                        $v = '#false';
                        break;
                    default :
                        break;
                }
                $key .= $key ? '_'.$v : $v;
            }
        else if($keyArray === '') $key .= 'default';
        else $key .= $keyArray;
        return $this->cacheRoot .$dir.$key.'/';
    }
    // 执行方法
    private function runFunc($obj, $func, $args){
        if (method_exists($obj, 'runProtectedFunction')) return $obj->runProtectedFunction($func, $args);
        else return call_user_func_array(array($obj, $func), $args);
    }
    // 缓存方法 兼容 return 与 打印输出
    public function cacheCall($obj, $func, $cacheTime=true){
        if($cacheTime === true ) $cacheTime = false;
        $pars = func_get_args();
        unset($pars[0]);
        unset($pars[1]);
        unset($pars[2]);
        $par = array_values($pars);
        $cachedir = $this->makeCacheDir($obj, $func, $par);
        if($re = $this->getCache($cachedir, $cacheTime)  )
            return $re['data'];
        else{
            ob_start();
            $return  = $this->runFunc($obj, $func, $par);
            $echo = ob_get_contents();
            ob_end_flush();
            $this->saveFile($cachedir.'echo.html', $echo);
            $this->saveFile($cachedir.'return.html', serialize($return));
            return $return;
        }
    }
    // 清除缓存, 可指定
    public function cacheClear($App='', $Contr='', $Func=''){
        $dirName = ROOT.'data/Cache/';
        $dirName .= $App ? $App.'App/' : '';
        $dirName .= $Contr ? $Contr : '';
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
        }return false;
    }
    private function _mkdir($dir, $mode = 0777 ){
        if(is_dir(dirname($dir)) || $this->_mkdir(dirname($dir))) return mkdir($dir, $mode);
    }
}
