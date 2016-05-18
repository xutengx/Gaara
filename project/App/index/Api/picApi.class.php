<?php
namespace App\index\Api;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class picApi extends Controller\RestController{
    public function get(array $data){
        echo 'i am get of pic';
    }
    public function put(array $data){
        echo 'i am put of pic';
    }
    public function post(array $data){
        echo 'i am post of pic';
    }
    public function delete(array $data){
        echo 'i am delete of pic';
    }
}