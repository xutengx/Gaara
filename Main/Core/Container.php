<?php

declare(strict_types = 1);
namespace Main\Core;

use Exception;
/**
 * 快捷类
 */
abstract class Container {

    protected static $instance = null;

    /**
     * 返回自快捷类对应的实体类的实例
     * @return object
     */
    final public static function getInstance() {
        return \obj(static::getInstanceName());
    }

    /**
     * 返回自快捷类对应的实体类的类名
     * @return string
     * @throws Exception
     */
    final public static function getInstanceName(): string {
        if (!is_null(static::$instance))
            return static::$instance;
        else {
            $class = static::class;
            if (is_file(ROOT.'Main/Core/' . $class.'.php')) {
                return 'Main\Core\\' . $class;
            }elseif(is_file(ROOT.'Main/Expand/' . $class.'.php')) {
                return 'Main\Expand\\' . $class;
            }
        }
        throw new Exception('Alias class: "' . $class . '" not properly defined!');
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
