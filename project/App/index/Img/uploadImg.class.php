<?php
namespace App\index\Img;
defined('IN_SYS')||exit('ACC Denied');
class uploadImg  {
    // 存储webScoket上传的2进制图片
    // return get路径
    public function saveImg(){
        $data = $this->post();
        // 查重
        foreach($_FILES as $k=>$v){
            $ext = strrchr($v['name'], '.');
            $filename = obj('tool')->makeFilename('data/upload/img/','jpg');
            $fname = obj('tool')->makeFilename('data/upload/'.date('Ymd', time()), $ext);
            move_uploaded_file($v['tmp_name'], $fname);
            // 压缩
        }
        return $filename;
    }
}