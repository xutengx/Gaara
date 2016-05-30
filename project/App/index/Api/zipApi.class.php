<?php
namespace App\index\Api;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class zipApi extends Controller\RestController{
    public function get( $data){
//        var_dump($_SERVER['PATH_INFO']);
//        var_dump($this->getAcceptType());
        echo 'i am get of zip';
        $this->returnData($data);
    }
    public function put( $data){
//        var_dump($data);
        echo 'i am put of zip';
    }
    public function post($data){
//        var_dump($_SERVER['CONTENT_TYPE']);
//        parse_str(file_get_contents('php://input'), $vars);
//        echo 123;
//        var_dump(headers_sent());
//        var_dump(obj('f')->put);
////        var_dump($this->_type);
//        echo 'i am post of zip';
        $this->returnData($data);
    }
    public function delete( $data){
        var_dump($data);
        echo 'i am delete of zip';
    }
}