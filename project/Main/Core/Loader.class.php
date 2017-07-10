<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Loader{
    // 缓存对象,实现单元素模式
    private static $obj_ins = array();
    // 预存的class引用路径
    private static $obj_map = array(
//        'Main\Core\Module'=>'Main/Core/Module.class.php',
        'HTMLPurifier'=>'Main/Support/Secure/htmlpurifier-4.7.0/library/HTMLPurifier.includes.php',
        'QRcode'    =>'Main/Support/Image/QRcode.class.php',
    );
    // class简称
    private static $obj_call = array(
        'f'=>'\Main\Core\F',
        'm'=>'\Main\Core\Model',
        'mysql'=>'\Main\Core\Mysql',
        'conf'=>'\Main\Core\Conf',
        'secure'=>'\Main\Core\Secure',
        'template'=>'\Main\Core\Template',
        'log'=>'\Main\Core\Log',
        'tool'=>'\Main\Core\Tool',
        'cache'=>'\Main\Core\Cache',
        'session'=>'\Main\Core\Session',
    );
    /**
     * 通过全局obj()调用
     * @param string    $class      类名(应用类,可不带namespace,支持别名)
     * @param bool|true $singleton  单例模式实例化
     * @param array     $pars       new一个对象所需要的参数; 注:单例模式下,显然只有第一次实例化时,参数才会被使用!
     *
     * @return mixed
     * @throws Exception
     */
    public static function get($class = '', $singleton = true, array $pars = array()){
        $class = str_replace('/','\\',$class);
        // 别名修正
        if(isset(self::$obj_call[ strtolower($class) ]))
            $class = self::$obj_call[ strtolower($class) ];
        $class = trim($class,'\\');
        // 属于应用类,则进行添加 namespace 操作
        $class = self::checkClass($class);
        return self::getins($class, $singleton, $pars);
    }
    // 自动引入
    public static function requireClass($class){
        $path = ROOT.str_replace('\\','/',$class).'.class.php';
        // 根据预存的class引用路径
        if(isset(self::$obj_map[$class])) self::includeWithException(ROOT.self::$obj_map[$class]);
        else if (strtolower(substr($class, -5)) == 'model')  self::autoMakeModel($path, $class);
        else if (strtolower(substr($class, -3)) == 'obj')  self::autoMakeObject($path, $class);
        else self::includeWithException($path);
//        else self::includeWithException(ROOT . 'Include/' . $class . '.class.php');
    }
    // 自动生成 Model
    private static function autoMakeModel($path, $classname){
        if(file_exists($path) || obj('\Main\Core\Code')->makeModule($path, $classname) ) require $path;
    }
    // 自动生成 Object
    private static function autoMakeObject($path, $classname){
        if(file_exists($path) || obj('\Main\Core\Code')->makeObject($path, $classname) ) require $path;
    }
    // 异常处理
    private static function includeWithException($where){
        if(file_exists($where)) {
            require $where;
            return true;
        }
//        throw new Exception('引入文件 '.$where.' 不存在! ',99);
    }
    /**
     * 处理应用类 Contr Module Object or 自定义
     * @param string $class
     *
     * @return string $class
     */
    private static function checkClass($class=''){
        if( preg_match('#[A-Z]{1}[0-9a-z_]+$#', $class, $type) ){
            $array = explode('\\',$class);
            if(strrpos($class, '\\') !== false){
                if(count($array) == 2 )
                    return 'App\\'.$array[0].'\\'.$type[0].'\\'.$array[1];
            }else return 'App\\'.APP.'\\'.$type[0].'\\'.$class;
        }
        return $class;
    }
    /**
     * 缓存 class 的单例并返回实例
     * @param string     $class     完整类名
     * @param bool|true  $singleton 是否单利
     * @param null|array $par       参数数组
     *
     * @return mixed
     * @throws Exception
     */
    private static function getins($class, $singleton = true, $par = NULL){
        if(!class_exists($class))
            throw new Exception('实例化类 : '.$class.' 不存在!',99);
        $parstr = '';
        if($par !== NULL){
            $par = array_values($par);
            for( $i = 0 ; $i < count($par) ; $i++ )
                $parstr .= ',$par['.$i.']';
            $parstr = ltrim($parstr, ',');
        }
        $str = 'new $class('.$parstr.');';
        if($singleton === true){
            if(!isset(self::$obj_ins[ $class ]))
                eval('self::$obj_ins[$class] = '.$str);
            return self::$obj_ins[ $class ];
        }else return eval('return '.$str);
    }
    // 查看预存的class引用路径
    public static function showMap(){
        var_export(self::$obj_map);
    }

    /**
     *  清除所有对象缓存
     *  用于隔离子进程之间的资源句柄
     */
    public static function unsetAllObj(){
        self::$obj_ins = array();
        return true;
    }
    /**
     * 注册自定义的类引入
     * @param string $class 注册的类名
     * @param string $dir   require 路径(相对路径)
     *
     * @throws Exception
     */
    public static function putobj($class='', $dir=''){
        if(isset(self::$obj_map[$class])) throw new Exception($class.'已被注册!');
        self::$obj_map[$class] = $dir;
    }
}
spl_autoload_register(array('Main\Core\loader', 'requireClass'));