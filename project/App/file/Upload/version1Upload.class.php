<?php
namespace App\file\Upload;
defined('IN_SYS') || exit('ACC Denied');

class version1Upload {
    
    // 网站根目录
    private $config = [
        'scheme' => 'http',                         // 网站根目录
        'host'   => '192.168.43.128',               // 网络访问地址
        'save_path' => './data/upload/public/',     // 存储路径
        'key'       => 'version1'                   // 加密key
    ];
    
    
    public function __construct(){
        $this->config = obj('commonConfig')->version1;
    }
    
    /*
     * 将文件路径转化为网路路径
     * https://192.168.43.128/git/php_/project/index.php?path=file/index/download/file_name/__FILENAME__/version/1/download/false/
     */
    private function make_file_name($file_path){
        obj('tool')->absoluteDir($file_path);
        $scheme = $this->config['scheme'];                // 'http';
        $host = $this->config['host'];                  // '192.168.43.128';
        $pars = [
            'file_name' => obj('Secure')->encrypt(($file_path), $this->config['key']),
            'version' => 1,
            'download' => 'false'
        ];
        $where = 'file/index/download/';
        foreach ($pars as $k => $v) {
            $where .= $k . '/' . $v . '/';
        }
        $url = $scheme . '://' . $host . $_SERVER['SCRIPT_NAME'] . '?' . PATH . '=' . $where;    
        return $url;
    }
    
    // 上传入口
    public function upload(){
        $time = date('Ymd_Hi');
        $data = [];
        foreach($_FILES as $v){
            $file_url = $host = $this->config['save_path'] . $time ;
            $ext = substr(strrchr($v['name'], '.'), 1);
            $file_name = obj('tool')->makeFilename($file_url, $ext);
            $re = obj('tool')->printInFile( $file_name, file_get_contents($v['tmp_name']));
            if($re) {
                $data[$v['name']] = $this->make_file_name($file_name);
            }else{
                $data[$v['name']] = false;
            }
        }
        return $data;
    }
    
    // 获取入口
    public function download($file_name_base64_encrypt, $download){
        $file_name = obj('Secure')->decrypt($file_name_base64_encrypt, $this->config['key']); // /mnt/hgfs/www/git/php_/project/data/upload/public/20170606_1135/1235936230b1ac15.png        
        $name = basename($file_name);                           // 1235936230b1ac15.png    
        $path = strtr($file_name,[$name => '']);                // /mnt/hgfs/www/git/php_/project/data/upload/public/20170606_1135/
        $ext = substr(strrchr($name, '.'), 1);                  // png
        
        if($download !== 'false')
            obj('tool')->download($path, $name);
        else{
            header(obj('commonConfig')->getContentType($ext));
            exit(file_get_contents($file_name));
        }
    }
}
