<?php

declare(strict_types = 1);
namespace Gaara\Core\Request;

use Gaara\Core\Request\Component\File;
use Iterator;

/**
 * \Gaara\Core\Request->file
 */
class UploadFile implements Iterator {

    private $_items = [];

    /**
     * 加入一个文件对象
     * @param array $fileInfo
     */
    public function addFile(array $fileInfo): void {
        $file = new File;
        foreach ($fileInfo as $k => $v) {
            $file->{$k} = $v;
        }
        $this->_items[$file->key_name] = $file;
    }

    /**
     * 删除保存的文件,一般情况下在数据库回滚时调用
     * @return void
     */
    public function cleanAll(): void {
        foreach ($this->_items as $file) {
            $file->clean();
        }
    }

    /**
     * 获取 File 对象
     * @param string $attr
     * @return File
     */
    public function __get(string $attr): File {
        if (isset($this->_items[$attr])) {
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
