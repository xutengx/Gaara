<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/21 0021
 * Time: 17:12
 */
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Conf{
    protected $data = array();

    final public function __construct(){
        include   (ROOT.'config.inc.php');
        $this->data = $_CFG;						//配置文件信息,读过来,赋给data属性
        $this->makeDefine();
        $this->set();
    }
    final protected function __clone(){				//反克隆
        exit();
    }
    public function __get($key){					//用魔术方法,读取data内的信息
        if(array_key_exists($key, $this->data)) return $this->data[$key];
        else return null;
    }
    public function __set($key,$value){			// 用魔术方法,在运行期,动态增加或改变配置选项
        $this->data[$key] = $value ;
    }
    private function makeDefine(){
        define('PATH', $this->data['path']);
        define('SESSIONPATH', $this->data['sessionPath']);
        define('SESSIONLIFE', $this->data['sessionLife']);
        define('APPID', $this->data['appid']);
        define('APPSECRET', $this->data['appsecret']);
        if(isset($this->data['appid_test'])){
            define('APPID_TEST', $this->data['appid_test']);
            define('APPSECRET_TEST', $this->data['appsecret_test']);
        }
    }
    private function set(){
        date_default_timezone_set($this->data['timezone']);
    }
}