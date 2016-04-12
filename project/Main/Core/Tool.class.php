<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Tool{
    // 路径 转 绝对路径
    // param string &$dir
    // return string
    final public function absoluteDir(&$dir){
        $system = php_uname('s');
        $dir = str_replace('\\','/',trim($dir));
        if(substr($system,0,5) === 'Linux'){
            $pos = strpos($dir, '/');
            if($pos === false || $pos !== 0) $dir = ROOT.$dir;
        }else if(substr($system,0,7) === 'Windows'){
            $pos = strpos($dir, ':');
            if($pos === false || $pos !== 1) $dir = ROOT.$dir;
        }else exit('未兼容的操作系统!');
    }
    // 分割下载
    final public function download2($path, $name, $showname){
        $this->absoluteDir($path);
        $filename = $path.$name;
        $file  = $filename;
        if (FALSE!== ($handler = fopen($file, 'r')))
        {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.$showname.'.zip');
            header('Content-Transfer-Encoding: chunked');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            while(false !== ($chunk = fread($handler,4096)))
            {
                echo $chunk;
            }
        }
        exit;
    }
    // 推送下载文件
    // param  路径  文件名
    final public function download($path, $name){
        $this->absoluteDir($path);
        $filename = $path.$name;
        $file = fopen($filename,"r");
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length:".filesize($filename));
        header("Content-Disposition: attachment; filename=".$name);
        echo fread($file,filesize($filename));
        fclose($file);
        exit();
    }
    // curl发送post请求
    // test
    final public function sendPost($url, array $data=array()){
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        print_r($data);
    }
    // curl发送get请求
    // test
    final public function sendGet($url, array $data=array()){
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        print_r($data);
    }
    //参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
    // test
    final public function request($url,$post='',$cookie='', $returnCookie=0){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        if($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        }else{
            return $data;
        }
    }
    // 递归删除 目录(绝对路径)下的所有文件,不包括自身
    // return void
    final public function delDirAndFile($dirName){
        $this->absoluteDir($dirName);
        if (is_dir($dirName) && $dir_arr = scandir($dirName)){
            foreach($dir_arr as $k=>$v){
                if($v == '.' || $v == '..'){}
                else{
                    if(is_dir($dirName.'/'.$v)){
                        $this->delDirAndFile($dirName.'/'.$v);
                        rmdir($dirName.'/'.$v);
                    }else unlink($dirName.'/'.$v);
                }
            }
        }
    }
    // 将任意内容写进文件
    // param string $fileName 文件名
    // param string $text 内容
    // return bool
    final public function printInFile($fileName, $text){
        //if( ! $fileName || ! $text ) return false;
        $this->absoluteDir($fileName);
        if(strripos($fileName, '/') === (strlen($fileName) - 1)) return false;      // filename 为路径,而不是文件名
        if(!file_exists($fileName)){
            if(is_dir(dirname($fileName)) || $this->__mkdir(dirname($fileName))) touch($fileName);
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

    /**
     * @param $dirName 文件夹
     * @return array 返回文件夹下的所有文件 组成的一维数组
     * @throws Exception
     */
    final public function getFiles($dirName){
        $arr = array();
        if (is_dir($dirName) && $dir_arr = scandir($dirName)){
            foreach($dir_arr as $k=>$v){
                if($v == '.' || $v == '..'){}
                else{
                    if(is_dir($dirName.'/'.$v)){
                        $arr = array_merge($arr,  $this->getFiles($dirName.'/'.$v));
                    }else {
                        $arr[] = $dirName.'/'. $v;
                    }
                }
            }
            return $arr;
        }else throw new Exception($dirName.' 并非可读路径!');
    }
    // 递归创建目录
    // param string $dir 目录名
    // param string $mode 目录权限
    // return void
    final public function __mkdir($dir, $mode = 0777 ){
        $this->absoluteDir($dir);
        if(is_dir(dirname($dir)) || $this->__mkdir(dirname($dir))) return mkdir($dir, $mode);
    }
}