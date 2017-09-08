<?php

declare(strict_types = 1);
namespace Main\Core;
defined('IN_SYS') || exit('ACC Denied');

abstract class Container {

    protected static $instance = null;

    final public static function getInstance() {
        return \obj(static::getInstanceName());
    }

    final public static function getInstanceName() {
        if (!is_null(static::$instance))
            return static::$instance;
        else {
            $class = static::class;
            if (class_exists('Main\Core\\' . $class)) {
                return 'Main\Core\\' . $class;
            }
        }
        throw new Exception('别名类: ' . $class . ' 的 $instance 没有被正确定义!');
    }

    final public function __set(string $param, $value) {
        return static::getInstance()->$param = $value;
    }

    final public function __get(string $name) {
        return static::getInstance()->$name;
    }

    final public function __invoke() {
        $param = func_get_args();
        return static::getInstance()(...$param);
    }

    final public function __call(string $method, array $args) {
        $instance = static::getInstance();
        return $instance->$method(...$args);
    }

    final public static function __callStatic(string $method, array $args) {
        $instance = static::getInstance();
        return $instance->$method(...$args);
    }
}
