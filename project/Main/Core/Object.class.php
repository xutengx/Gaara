<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Object extends Base{
    // 对象属性
    protected $obj_attr = array();
    // 绑定模型
    protected $obj_module = null;
    // 绑定模型的选择条件
    protected $obj_where = null;

    final public function __construct(){
        $this->construct();
    }
    protected function construct(){

    }
    /**
     * 绑定数据库模型到obj
     * @param $obj 如 obj('userModule')
     * @param $where obj('userModule')->selRow($where);
     * @return $this
     */
    final public function init($obj, $where){
        $this->obj_module = $obj;
        $this->obj_attr = $obj->selRow($where);
        $this->obj_where = $where;
        return $this;
    }
    /**
     * 更新当前对象属性到绑定的数据库模型
     * @return bool|int
     */
    final public function save(){
        return $this->obj_module->modifyData($this->obj_attr);
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