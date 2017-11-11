<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Exception;
/**
 * 快捷类
 */
abstract class Container {

    /**
     * 返回自快捷类对应的实体类的实例
     * @return object
     */
    final public static function getInstance() {
        return obj(static::getInstanceName());
    }

    /**
     * 返回自快捷类对应的实体类的类名
     * @return string
     * @throws Exception
     */
    final public static function getInstanceName(): string {
        if (is_file(ROOT.'Gaara/Core/' . static::class.'.php')) {
            return 'Gaara\Core\\' . static::class;
        }elseif(is_file(ROOT.'Gaara/Expand/' . static::class.'.php')) {
            return 'Gaara\Expand\\' . static::class;
        }
        throw new Exception('Alias class: "' . static::class . '" not properly defined!');
    }

    final public function __set(string $param, $value) {
        return static::getInstance()->$param = $value;
    }

    final public function __get(string $name) {
        return static::getInstance()->$name;
    }

    final public function __invoke(...$params) {
        return static::getInstance()(...$params);
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
