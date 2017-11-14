<?php

namespace App\Dev\file\Contr;

use \Gaara\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');
/*
 * 文件服务, 路由类
 */
class indexContr extends Controller\Controller {

    public function upload() {
        $version = $this->post('version');
        switch ($version) {
            case '1':
                $res = obj('version1Upload')->upload();
                break;
            default:
                return $this->returnMsg(0, 'version无效');
        }
        return $this->returnData($res);
    }

    public function download() {
        $version = $this->get('version');
        switch ($version) {
            case '1':
                $download = $this->get('download');
                $file_name = $this->get('file_name');
                obj('version1Upload')->download($file_name, $download);
                break;
            default:
                return $this->returnMsg(0, 'version无效');
        }
    }
}
