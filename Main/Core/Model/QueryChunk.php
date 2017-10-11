<?php

declare(strict_types = 1);
namespace Main\Core\Model;

use PDOStatement;
use Iterator;
/**
 * 块状数据对象
 */
class QueryChunk implements Iterator {
    
    public $PDOStatement;
    // 当前位置是否有效
    private $is_over = true;
    // 当前元素的键
    private $key = null;
    // 当前元素的值
    private $value = [];
    
    public function __construct(PDOStatement $PDOStatement) {
        $this->PDOStatement = $PDOStatement;
    }
    
    
    /************************************************ 以下 Iterator 实现 ***************************************************/

    private function fetchData(){
        $value = $this->PDOStatement->fetch(\PDO::FETCH_ASSOC);
        if($value === false){
            $this->is_over = false;
        }else{
            $this->key = is_null($this->key) ? 0 : ++$this->key ;
            $this->value = $value;
        }
    }
    
    public function rewind() {
        $this->key = null;
        $this->is_over = true;
        $this->fetchData();
    }

    public function current() {
        return $this->value;
    }

    public function key() {
        return $this->key;
    }

    public function next() {
        $this->fetchData();
//        $value = $this->PDOStatement->fetch(\PDO::FETCH_ASSOC);
//        if($value === false){
//            $this->is_over = false;
//        }else{
//            $this->key++;
//            $this->value = $value;
//        }
    }

    public function valid() {
        return $this->is_over;
    }
}