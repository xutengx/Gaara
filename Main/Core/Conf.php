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
                self::$env[str_ireplace($this->key, '', $k)] = $v;
            } else if (!isset(self::$env[$k])) {
                self::$env[$k] = $v;
            }
        }
    }

    /**
     * 获取环境变量, function env 指向此
     * @param string $name
     * @param mix $default  当此变量不存在时的默认值
     * @return mix
     */
    public function getEnv(string $name, $default = null) {
        return self::$env[$name] ?? $default;
    }

    /**
     * 惰性读取配置文件
     * @param string $configName
     * @return mix
     */
    public function __get(string $configName) {
        if (array_key_exists($configName, self::$data)) {
            return self::$data[$configName];
        } elseif (is_file(CONFIG . $configName . '.php')) {
            $config = require(CONFIG . $configName . '.php');
            self::$data[$configName] = $config;
            return $config;
        } else
            return null;
    }

    /**
     * 返回指定的配置文件  
     * eg Conf::db('_dev');     将返回以 _dev为键的配置
     * @param string $name
     * @param array $arguments
     * @return mix
     * @throws Exception
     */
    public function __call(string $name, array $arguments) {
        // 存在对应配置文件
        if (isset(self::$data[$name])) {
            return self::$data[$name][reset($arguments)];
        } elseif (is_file(CONFIG . $name . '.php')) {
            $config = require(CONFIG . $name . '.php');
            self::$data[$name] = $config;
            return $config[reset($arguments)];
        } else {
            throw new Exception('配置文件不存在' . $name);
        }
    }

    public function __set(string $key, $value): void {
        $this->data[$key] = $value;
    }

}
