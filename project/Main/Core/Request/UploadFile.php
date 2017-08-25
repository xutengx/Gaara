<?php

declare(strict_types = 1);
namespace Main\Core\Request;
defined('IN_SYS') || exit('ACC Denied');

use Main\Core\Request\Tool\Component;
use Iterator;
/**
 * \Main\Core\Request->file
 */
class UploadFile implements Iterator {
    
    private $_items = [];
    /**
     * 加入一个文件
     * @param \Main\Core\Request\Main\Core\Request\Tool\File $fileInfo
     */
    public function addFile(array $fileInfo):void{
        $file = new File;
        foreach($fileInfo as $k => $v){
            $file->{$k} = $v;
        }
        $this->_items[$file->key_name] = $file;
    }
    
    public function __get(string $attr){
        if(isset($this->_items[$attr])){
            return $this->_items[$attr];
        }
    }
    
/************************************************ 以下 Iterator 实现 ***************************************************/
    public function rewind() {
        reset($this->_items);
    }

    public function current() {
        return current($this->_items);
    }

    public function key() {
        return key($this->_items);
    }

    public function next() {
        return next($this->_items);
    }

    public function valid() {
        return ( $this->current() !== false );
    }
}
