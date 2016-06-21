<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
abstract class Object{
    // 对象属性
    protected $obj_attr = array();
    // 绑定模型
    protected $obj_module = null;

    final public function __construct(){
        $this->construct();
    }
    protected function construct(){

    }
    /**
     * 绑定数据库模型到obj
     * @param object $obj 如 obj('userModule')
     * @return $this
     */
    final public function init($obj){
        $this->obj_module = $obj;
        if(!$this->obj_attr = $this->bind())
            $this->bindFalse();
        return $this;
    }

    /**
     * 绑定 model 到 obj
     * @return array|false 一维数组
     */
    abstract protected function bind();

    /**
     * bind() 失败(returne false)之后操作
     * @return mixed
     */
    abstract protected function bindFalse();

    /**
     * 更新当前对象属性到绑定的数据库模型
     * @return bool|int
     */
    final public function save(){
        return $this->obj_module->updateData($this->obj_attr);
    }
    final public function __set($property_name, $value){
        $this->obj_attr[$property_name] = $value;
    }
    final public function __get($property_name){
        if(isset($this->obj_attr[$property_name]))
            return $this->obj_attr[$property_name];
        else throw new Exception('不存在的属性!');
    }
}