<?php

namespace Main\Core;

defined('IN_SYS') || exit('ACC Denied');

class Conf {

    // 配置存放数组
    protected $data = array();
    // 多配置选取关键字
    protected $key = '_test';

    final public function __construct() {
        $this->getConfig();
        $this->set();
    }

    public function __get($key) {
        if (array_key_exists($key, $this->data))
            return $this->data[$key];
        else
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
                $this->data[str_ireplace($this->key, '', $k)] = $data[$k];
            } else if (!isset($this->data[$k])) {
                $this->data[$k] = $data[$k];
            }
        }
    }

    private function set() {
        date_default_timezone_set($this->data['timezone']);
        if ($this->data['debug'] === true) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        } else
            ini_set('display_errors', 0);
    }
//    public function getCreateDb(){
//        return require(ROOT.'db.inc.php'); 
//    }
}
