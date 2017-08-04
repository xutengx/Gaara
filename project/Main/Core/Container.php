<?php

namespace Main\Core;
defined('IN_SYS') || exit('ACC Denied');

abstract class Container {
    
    protected static $instance = null;

    public function __set($param, $value) {
        return \obj(static::$instance)->$param = $value;
    }

    public function __get($name) {
        return \obj(static::$instance)->$name;
    }

    public function __invoke() {
        $param = func_get_args();
        return \obj(static::$instance)(...$param);
    }

    public function __call($method, $args) {
        $instance = \obj(static::$instance);
        return $instance->$method(...$args);
    }

    public static function __callStatic($method, $args) {
        $instance = \obj(static::$instance);
        return $instance->$method(...$args);
    }

}
