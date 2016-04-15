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
    // 多配置选取关键字
    protected $key = '_test';

    final public function __construct(){
        $this->choose();
        $this->getConfig();
        $this->makeDefine();
        $this->set();
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
        define('DEBUG', $this->data['debug']);
        define('MINJS', $this->data['minjs']);
    }
    private function set(){
        date_default_timezone_set($this->data['timezone']);
        if(DEBUG == true) ini_set('display_errors', 1);
        else ini_set('display_errors', 0);
    }
    public function getCreateDb(){
        return require(ROOT.'db.inc.php'); //配置文件信息,读过来,赋给data属性
    }
    /**
     *  当前应用的配置
     */
    private function getConfig(){
        $data =  require(ROOT.'config.inc.php'); //配置文件信息,读过来,赋给data属性
        foreach($data as $k=>$v){
            if (strpos($k, $this->key)){
                $this->data[str_ireplace($this->key,'',$k)] =  $data[$k];
            }else if(!isset($this->data[$k])){
                $this->data[$k] =  $data[$k];
            }
        }
    }
    // 多配置共存时,选择拥由后缀的项优先级最高;
    // 设定当前应用的配置
    private function choose(){
        if($_SERVER['HTTP_HOST'] == 'poster.issmart.com.cn'){
            $this->key = '_poster';
        }else if($_SERVER['HTTP_HOST'] == 'wx.issmart.com.cn'){
            $this->key = '_wx';
        }
    }
}