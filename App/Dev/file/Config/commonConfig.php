<?php
namespace App\Dev\file\Config;
defined('IN_SYS') || exit('ACC Denied');

final class commonConfig {
    
    private $version1 = [
//        'scheme' => 'http',                         // 网站根目录
//        'host'   => '192.168.43.128',               // 网络访问地址
        'save_path' => './data/upload/public/',     // 存储路径
        'key'       => 'version1'                   // 加密key
    ];

    
    private $contentType = [
        'gif'   =>'Content-type: image/gif',
        'png'   =>'Content-type: image/png',
        'jpeg'  =>'Content-type: image/jpeg',
        'jpg'   =>'Content-type: image/jpeg',
        'json'  =>'Content-type: application/json',
        'js'    =>'Content-type: text/javascript',
        'atom'  =>'Content-type: application/atom+xml',
        'pdf'   =>'Content-type: application/pdf',
        'rss'   =>'Content-Type: application/rss+xml; charset=ISO-8859-1',
        'css'   =>'Content-type: text/css',
        'xml'   =>'Content-type: text/xml',
        'mp3'   =>'Content-Type: audio/mpeg',
        'flash' =>'Content-Type: application/x-shockw**e-flash',
        'zip'   =>'Content-Type: application/zip',
        'txt'   =>'Content-Type: text/plain',
    ];
    
    final public function __get($par){
        return $this->$par;
    }
    
    final public function getContentType($key){
        return (array_key_exists($key, $this->contentType)) ? $this->contentType[$key] : 'Content-Type: text/plain';        
    }
}
