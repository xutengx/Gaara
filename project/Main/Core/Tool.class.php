<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Tool{
    /**
     * 路径 转 绝对路径
     * @param string $dir
     */
    final public function absoluteDir(&$dir=''){
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
    final public function sendPost($url, array $data=array()){
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出 1 要头 0 不要
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //执行命令
        $re = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $re;
    }
    // curl发送get请求
    final public function sendGet($url, array $data=array()){
        if(!empty($data)){
            $query = http_build_query($data);
            $url .= strpos($url, '?') ? '&'.$query : '?'/$query;
        }
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出  1 要头 0 不要
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $re = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        print_r($re);
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
    /**
     * 递归删除 目录(绝对路径)下的所有文件,不包括自身
     * @param string $dirName 目录
     *
     * @return void
     */
    final public function delDirAndFile($dirName=''){
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
    /**
     * 将任意内容写进文件
     * @param string $fileName  文件名
     * @param string $text  内容
     *
     * @return bool
     */
    final public function printInFile($fileName='', $text=''){
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
     * @param string $dirName 文件夹
     *
     * @return array 返回文件夹下的所有文件 组成的一维数组
     * @throws Exception
     */
    final public function getFiles($dirName=''){
        $dirName = rtrim($dirName,'/');
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
    /**
     * 人性化相对时间
     * @param int    $sTime 目标时间
     * @param string $format 时间格式
     *
     * @return bool|string
     */
    final public function friendlyDate($sTime=0, $format='Y-m-d H:i'){
        $dTime      =   time() - $sTime;
        $state      =   $dTime>0?'前':'后';
        $dTime      =   abs($dTime);
        if($dTime < 60 ){
            return $dTime . ' 秒'.$state;
        } else if($dTime < 3600 ){
            return intval($dTime/60) . ' 分钟'.$state;
        } else if($dTime < 3600*24 ){
            return intval($dTime/3600) . ' 小时'.$state;
        } else if($dTime < 3600*24*7 ){
            return intval($dTime/(3600*24)) . ' 天'.$state;
        } else return date($format, $sTime);
    }
    /**
     * 生成随机文件名
     * @param string $dir 文件所在的目录
     * @param string $ext 文件后缀
     * @param int    $id  唯一标识
     *
     * @return string
     */
    final public function makeFilename($dir='', $ext='', $id=123){
        $ext = $ext ? $ext : '';
//        $this->absoluteDir($dir);
        $dir = $dir?rtrim($dir,'/').'/':'./';
        if(!is_dir($dir)) $this->__mkdir($dir);
        $ext = trim($ext,'.');
        $dir .= uniqid($id);
        $dir .='.'.$ext;
        return $dir;
    }
    /**
     * 递归创建目录
     * @param string $dir 目录名(相对or绝对路径)
     * @param int    $mode 目录权限
     *
     * @return bool
     */
    final public function __mkdir($dir='', $mode = 0777 ){
        $this->absoluteDir($dir);
        if(is_dir(dirname($dir)) || $this->__mkdir(dirname($dir))) return mkdir($dir, $mode);
    }
    /**
     * 字符串长度控制(截取)
     * @param string     $string 原字符串
     * @param int        $length 目标长度
     * @param bool|false $havedot 多余展示符,false则没有, 如 ...
     * @param string     $charset 字符编码
     *
     * @return mixed|string
     */
    public function cutstr($string='', $length=9, $havedot = false, $charset = 'utf8'){
        if (strtolower($charset) == 'gbk') $charset = 'gbk';
        else $charset = 'utf8';
        if (strlen($string) <= $length)  return $string;
        if (function_exists('mb_strcut'))  $string = mb_substr($string, 0, $length, $charset);
        else {
            $pre = '{%';  $end = '%}';
            $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);
            $strlen = strlen($string);
            $n = $tn = $noc = 0;
            if ($charset == 'utf8') {
                while ($n < $strlen) {
                    $t = ord($string[$n]);
                    if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                        $tn = 1;
                        $n++;
                        $noc++;
                    } elseif (194 <= $t && $t <= 223) {
                        $tn = 2;
                        $n += 2;
                        $noc++;
                    } elseif (224 <= $t && $t <= 239) {
                        $tn = 3;
                        $n += 3;
                        $noc++;
                    } elseif (240 <= $t && $t <= 247) {
                        $tn = 4;
                        $n += 4;
                        $noc++;
                    } elseif (248 <= $t && $t <= 251) {
                        $tn = 5;
                        $n += 5;
                        $noc++;
                    } elseif ($t == 252 || $t == 253) {
                        $tn = 6;
                        $n += 6;
                        $noc++;
                    } else {
                        $n++;
                    }
                    if ($noc >= $length) {
                        break;
                    }
                }
                if ($noc > $length) {
                    $n -= $tn;
                }
                $strcut = substr($string, 0, $n);
            } else {
                while ($n < $strlen) {
                    $t = ord($string[$n]);
                    if ($t > 127) {
                        $tn = 2;
                        $n += 2;
                        $noc++;
                    } else {
                        $tn = 1;
                        $n++;
                        $noc++;
                    }
                    if ($noc >= $length) {
                        break;
                    }
                }
                if ($noc > $length) {
                    $n -= $tn;
                }
                $strcut = substr($string, 0, $n);
            }
            $string = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
        }
        if ($havedot) $string = $string . $havedot;
        return $string;
    }
    /**
     * 解析XML格式的字符串
     *
     * @param string $str
     * @return false|array 解析正确就返回解析结果,否则返回false,说明字符串不是XML格式
     */
    public function xml_decode($str){
        $bool = null;
        $xml_parser = xml_parser_create();
        if(xml_parse($xml_parser,$str,true))
            $bool = (json_decode(json_encode(simplexml_load_string($str)),true));
        xml_parser_free($xml_parser);
        return $bool;
    }
    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id   数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
     */
    public function xml_encode($data, $root='root', $item='item', $attr='', $id='id', $encoding='utf-8') {
        if(is_array($attr)){
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr   = trim($attr);
        $attr   = empty($attr) ? '' : " {$attr}";
        $xml    = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml   .= "<{$root}{$attr}>";
        $xml   .= $this->data_to_xml($data, $item, $id);
        $xml   .= "</{$root}>";
        return $xml;
    }

    /**
     * 数据XML编码
     * @param mixed  $data 数据
     * @param string $item 数字索引时的节点名称
     * @param string $id   数字索引key转换为的属性名
     * @return string
     */
    public function data_to_xml($data, $item='item', $id='id') {
        $xml = $attr = '';
        foreach ($data as $key => $val) {
            if(is_numeric($key)){
                $id && $attr = " {$id}=\"{$key}\"";
                $key  = $item;
            }
            $xml    .=  "<{$key}{$attr}>";
            $xml    .=  (is_array($val) || is_object($val)) ? data_to_xml($val, $item, $id) : $val;
            $xml    .=  "</{$key}>";
        }
        return $xml;
    }
}