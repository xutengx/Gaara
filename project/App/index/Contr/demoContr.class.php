<?php

namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class demoContr extends Controller\HttpController {

    private $upload_url = 'http://172.19.5.55/git/php_/project/index.php';

    public function indexDo() {
        $this->display();
    }

    public function upload() {
        foreach($_FILES as $v){
            $file_url = './data/' . time() . $v['name'];
            $temp[] = $file_url;
            if (move_uploaded_file($v['tmp_name'], $file_url)) {
                $data[$v['name']] = new \CURLFile(\realpath($file_url));
            }
        }
        $result = obj('Tool')->sendPost($this->upload_url, ($data));
        foreach($temp as $v){
            unlink($v);    
        }
        var_dump($result);exit;
        
        
    }
}
