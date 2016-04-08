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
//    protected static $ins = null;
    protected $data = array();
    protected $includeDir =  'Main/Views/include';

    final public function __construct(){
        include   (ROOT.'config.inc.php');
        $this->data = $_CFG;						//配置文件信息,读过来,赋给data属性
        $this->makeDefine();
        $this->set();
        $this->includeFiles();
    }
    final protected function __clone(){				//反克隆
        exit();
    }
//    public static function getins(){
//        if((self::$ins instanceof self) || (self::$ins = new self()))  return self::$ins;
//    }
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
    private function includeFiles(){
        $files = $this->getFiles($this->includeDir);
        $str = '';
        foreach($files as $k=>$v){
            $ext = strrchr($v , '.');
            switch ($ext) {
                case '.js' :
                    $str .= '<script src="'.$v.'"></script>';
                    break;
                case '.css' :
                    $str .= '<link rel="stylesheet" href="'.$v.'" />';
                    break;
                case '.ico' :
                    $str .= '<link rel="shortcut icon" href="'.$v.'" type="image/x-icon">';
                    break;
                default:
                    break;
            }
        }
        define('VIEW_INCLUDE',$str);
    }

    /**
     * @param $dirName 文件夹
     * @return array 返回文件夹下的所有文件 组成的一维数组
     * @throws Exception
     */
    private function getFiles($dirName){
        $arr = array();
        if (is_dir($dirName) && $dir_arr = scandir($dirName)){
            foreach($dir_arr as $k=>$v){
                if($v == '.' || $v == '..'){}
                else{
                    if(is_dir($dirName.'/'.$v)){
                        $arr = array_merge($arr,  $this->getFiles($dirName.'/'.$v));
                    }else {
                        $arr[] = $dirName.'/'. $v;
                    }
                }
            }
            return $arr;
        }else throw new Exception($dirName.' 并非可读路径!');
    }
}