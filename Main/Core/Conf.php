<?php

declare(strict_types = 1);
namespace Main\Core;

use Closure;

class Conf {

    // 配置信息
    private static $data = [];
    // 环境变量
    private static $env = [];
    // 环境变量选取关键字
    protected $key = '_test';

    final public function __construct() {
        $this->setEnv();
    }

    /**
     * 读取环境变量 env.php 并赋值给 self::$env
     * 包含多配置的选择
     * @return void
     */
    private function setEnv(): void {
        $data = require(ROOT . 'env.php');
        if (isset($data['selection'])) {
            $this->key = $data['selection'] instanceof Closure ? $data['selection']() : $data['selection'];
        }
        foreach ($data as $k => $v) {
            if (strpos($k, $this->key)) {
                self::$env[str_replace($this->key, '', $k)] = $v instanceof Closure ? $v() : $v;
            } elseif (!isset(self::$env[$k])) {
                self::$env[$k] = $v instanceof Closure ? $v() : $v;
            }
        }
    }

    /**
     * 获取环境变量, function env 指向此
     * @param string $name
     * @param mixed $default  当此变量不存在时的默认值
     * @return mixed
     */
    public function getEnv(string $name, $default = null) {
        return self::$env[$name] ?? $default;
    }

    /**
     * 惰性读取配置文件
     * @param string $configName
     * @return mixed
     */
    public function __get(string $configName) {
        if (array_key_exists($configName, self::$data)) {
            return self::$data[$configName];
        } elseif (is_file(CONFIG . $configName . '.php')) {
            return self::$data[$configName] = require(CONFIG . $configName . '.php');
        } 
    }

    public function __set(string $key, $value): void {
        $this->data[$key] = $value;
    }

}
