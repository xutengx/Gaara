<?php
namespace App\file\Contr;
use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class indexContr extends Controller\HttpController {
    
    // 网站根目录
    private $nginx_root = '/mnt/hgfs/www/';
    
    // 网络访问地址
    private $web_addr   = 'https://192.168.43.128/';


    public function upload(){
        $version = $this->post('version');
        switch ($version) {
            case '1':
                $this->version_1();
                break;
            default:
                return $this->returnMsg(0, 'version无效');
        }
    }
    
    /*
     * 将文件路径转化为网路路径
     * 
     */
    private function make_file_name($file_path){
        obj('tool')->absoluteDir($file_path);
        return strtr($file_path, [ $this->nginx_root => $this->web_addr ]);
    }


    private function version_1(){
        $time = date('Ymd_Hi');
        $data = [];
        foreach($_FILES as $v){
            $file_url = './data/upload/public/' . $time ;
            $ext = substr(strrchr($v['name'], '.'), 1);
            $file_name = obj('tool')->makeFilename($file_url, $ext);
            $re = obj('tool')->printInFile( $file_name, file_get_contents($v['tmp_name']));
            if($re) {
                $data[$v['name']] = $this->make_file_name($file_name);
            }else{
                $data[$v['name']] = false;
            }
        }
        return $this->returnData($data);
    }
}
