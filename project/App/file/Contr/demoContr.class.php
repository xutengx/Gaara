<?php
namespace App\file\Contr;
use \Main\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');

class demoContr extends Controller\HttpController {

    private $upload_url = 'http://192.168.43.128/git/php_/project/index.php?path=file/index/upload';

    public function indexDo() {
        $this->display();
    }

    public function upload_demo() {
        foreach($_FILES as $v){
            $file_url = './data/' . time() . $v['name'];
            $temp[] = $file_url;
            if (move_uploaded_file($v['tmp_name'], $file_url)) {
                $data[$v['name']] = new \CURLFile(\realpath($file_url));
            }
        }
        $data['version'] = 1;
        $result = obj('Tool')->sendPost($this->upload_url, ($data));
        foreach($temp as $v){
            unlink($v);    
        }
        var_dump($result);
        var_dump(json_decode($result));exit;
    }
}
