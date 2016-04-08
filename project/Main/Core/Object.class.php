<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Object extends Base{
    private $data = array();

    final public function __construct(){
        $this->construct();
    }
    protected function construct(){
    }
    public function __get($key){
        if(array_key_exists($key, $this->data)) return $this->data[$key];
        else return null;
    }
}