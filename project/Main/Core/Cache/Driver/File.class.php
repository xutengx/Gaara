<?php
namespace Main\Core\Cache\Driver;
use Main\Core\Cache\DriverInterface;
defined('IN_SYS')||exit('ACC Denied');
class File implements DriverInterface {
    // 缓存目录
    private $cacheRoot        ;
    // 缓存扩展名
    private $cacheFileExt     = "html";

    final public function __construct(){
        $this->cacheRoot = ROOT.'data/Cache/';
    }
    /**
     * 清除指定缓存
     * @param object|false $obj 执行对象
     * @param string|false $func 执行方法
     */
//    public function cacheClear($obj=false, $func=false){
//        $cachedir = $this->cacheRoot;
//        $cachedir .= $obj ? str_replace('\\','/',get_class($obj)) : '' ;
//        $cachedir .= $func ? '/'.$func : '';
//        $pars = func_get_args();
//        unset($pars[0]);
//        unset($pars[1]);
//        $keyArray = array_values($pars);
//        $key = '';
//        if(!empty($keyArray) ){
//            foreach($keyArray as $k=>$v){
//                if($v === true) $key .= '_boolean-true';
//                elseif($v === false) $key .= '_boolean-false';
//                else $key .= '_'.gettype($v).'-'.$v;
//            }
//            $cachedir .= '/'.$key.'/';
//        }
//        $this->del_DirAndFile($cachedir);
//    }


    public function set($key, $velue, $cacheTime){
        $filename = $this->makeFilename($key);
        $data    = "<?php\n//".sprintf('%012d',$cacheTime).serialize($velue)."\n?>";
        return $this->saveFile($filename,$data);
    }
    public function get($key){
        $filename = $this->makeFilename($key);
        if(!file_exists($filename)) return array('code'=> 0);
        $content    =   file_get_contents($filename);
        if( false !== $content) {
            $expire  =  (int)substr($content,8, 12);
            if(time() > filemtime($filename) + $expire) {
                //缓存过期删除缓存文件
                unlink($filename);
                return array('code'=> 0);
            }
            $content   =  substr($content,20, -3);
            $content    =   unserialize($content);
            return array(
                'code'=> 200,
                'data'=>$content
            );
        }
        else return array('code'=> 0);
    }
    public function rm($key){
        $filename = $this->makeFilename($key);
        if(file_exists($filename))
            return unlink($filename);
        return true;
    }
    public function clear($cachedir){
        $cachedir = $this->cacheRoot.$cachedir;
        return $this->del_DirAndFile($cachedir);
    }

    public function callget($cachedir, $cacheTime ){
        $echo = $this->cacheRoot.$cachedir.'echo.'.$this->cacheFileExt;
        $return = $this->cacheRoot.$cachedir.'return.'.$this->cacheFileExt;
        if(file_exists($echo) || file_exists($return)){
            $cTime = ($t = $this->getFileCreateTime($echo)) ? $t : $this->getFileCreateTime($return);
            if(($cTime + $cacheTime) > time()){
                $data = NULL;
                if(file_exists($echo))
                    echo file_get_contents($echo);
                if(file_exists($return))
                    $data = unserialize(file_get_contents($return));
                return array(
                    'code'=> 200,
                    'data'=>$data
                );
            }
        }
        return false;
    }
    public function callset($cachedir, $echo='', $return, $cacheTime){
        $this->saveFile($this->cacheRoot.$cachedir.'echo.'.$this->cacheFileExt, $echo);
        $this->saveFile($this->cacheRoot.$cachedir.'return.'.$this->cacheFileExt, serialize($return));
        clearstatcache();
        return array(
            'code'=> 200,
            'data'=>$return
        );
    }

    private function makeFilename($key){
        return $this->cacheRoot.'serialize/'.md5($key).'.php';
    }

    // 递归删除 目录(绝对路径)下的所有文件,包括自身
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
//            return rmdir($dirName);
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
