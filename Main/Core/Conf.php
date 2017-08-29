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
     * @param string $configName
     * @return mix
     */
    public function __get(string $configName) {
        if (array_key_exists($configName, self::$data)){
            return isset(self::$data[$configName][$this->key]) ? self::$data[$configName][$this->key] : self::$data[$configName];
        }
        elseif (file_exists(CONFIG . $configName . '.php')) {
            $config = require(CONFIG . $configName . '.php');
            self::$data[$configName] = $config;
            return isset($config[$this->key]) ? $config[$this->key] : $config;
        } else
            return null;
    }

    /**
     * 返回指定的配置文件  eg Conf::db('_dev');     将返回以 _dev为键的配置数组
     * @param string $name
     * @param string $arguments
     * @return mix
     */
    public function __call(string $name, array $arguments) {
        // 存在对应配置文件
        if(isset(self::$data[$name])){
            return self::$data[$name][reset($arguments)];
        }elseif(file_exists(CONFIG . $name . '.php')) {
            $config = require(CONFIG . $name . '.php');
            self::$data[$name] = $config;
            return $config[reset($arguments)];
        }else{
            throw new Exception('配置文件不存在' .$name.' '.$arguments);
        }
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
