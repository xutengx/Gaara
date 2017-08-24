<?php

namespace Main\Core\Request;
defined('IN_SYS') || exit('ACC Denied');

class File implements Iterator {

    public $name;
    public $type;
    public $content;
    public $size;
    public $tmp_name;
    public $key_name;
    private $_items = array(1, 2, 3, 4, 5, 6, 7);

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
