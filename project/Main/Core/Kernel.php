<?php

namespace Main\Core;

defined('IN_SYS') || exit('ACC Denied');

class Kernel {
    
    final public function __get($param) {
        return $this->$param;
    }
}
