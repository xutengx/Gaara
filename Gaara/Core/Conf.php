<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Closure;

class Conf {

    // 配置信息
    private static $data = [];
    // 环境变量
    private static $env = [];

    final public function __construct() {
        $this->setEnv();
    }

    /**
     * 读取环境变量 env.php 并赋值给 self::$env
     * 包含多配置的选择
     * @return void
     */
    private function setEnv(): void {
        $data = parse_ini_file(ROOT.".env", true);
        $env = $data['ENV'];
        self::$env = array_merge($data, $data[$env]);
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
