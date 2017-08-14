<?php

namespace Main\Core;

defined('IN_SYS') || exit('ACC Denied');

class Conf {

    // 配置存放数组
    private static $data = array();
    // 多配置选取关键字
    protected $key = '_test';

    final public function __construct() {
        $this->getConfig();
        $this->set();
    }
    /**
     * 惰性读取配置文件
     * @param type $key
     * @return type
     */
    public function __get($configName) {
        if (array_key_exists($configName, self::$data))
            return self::$data[$configName];
        elseif (file_exists(CONFIG . $configName . '.php')) {
            $config = require(CONFIG . $configName . '.php');
            return self::$data[$configName] = isset($config[$this->key]) ? $config[$this->key] : $config;
        } else
            return null;
    }

    public function __set($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * 多配置共存时,选择拥由后缀的项优先级最高;
     * 设定当前应用的配置
     */
    private function getConfig() {
        $data = require(ROOT . 'config.inc.php'); //配置文件信息,读过来,赋给data属性
        $this->key = $data['chooseConfig']();
        foreach ($data as $k => $v) {
            if (strpos($k, $this->key)) {
                self::$data[str_ireplace($this->key, '', $k)] = $data[$k];
            } else if (!isset($this->data[$k])) {
                self::$data[$k] = $data[$k];
            }
        }
    }

    private function set() {
        date_default_timezone_set(self::$data['timezone']);
        if (self::$data['debug'] === true) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        } else
            ini_set('display_errors', 0);
    }
    
}
