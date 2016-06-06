<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class uploadimgContr extends Controller\HttpController{
    // 存储webScoket上传的2进制图片
    public function saveImg(){
        // 查重
        foreach($_FILES as $k=>$v){
            $ext = strrchr($v['name'], '.');
            $fname = obj('tool')->makeFilename('data/upload/img/'.date('Ymd', time()), $ext);
            move_uploaded_file($v['tmp_name'], $fname);
            // 压缩
        }
        return true;
    }
}