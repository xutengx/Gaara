<?php
namespace Main;
defined('IN_SYS')||exit('ACC Denied');
class Object extends Base{
    protected static $ins        = null;
    private $data = array();

    final public function __construct(){
        $this->construct();
    }
    protected function construct(){
    }
    public static function getins(){
        if(static::$ins instanceof static || (static::$ins = new static)) return static::$ins;
    }
    public function __get($key){
        if(array_key_exists($key, $this->data)) return $this->data[$key];
        else return null;
    }
}