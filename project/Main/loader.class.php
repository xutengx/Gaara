<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/21 0021
 * Time: 17:11
 */
namespace Main;
defined('IN_SYS')||exit('ACC Denied');
class obj{
    // 缓存对象,实现单元素模式
    private static $obj_ins = array();
    // 预存的class引用路径
    private static $obj_map = array(
        'Main\Base'=>'Main/Base.class.php',
        'Main\Secure'=>'Main/Secure.class.php',
        'Main\Cache'=>'Main/Cache.class.php',
        'Main\conf'=>'Main/conf.class.php',
        'Main\Controller'=>'Main/Controller.class.php',
        'Main\F'=>'Main/F.class.php',
        'Main\log'=>'Main/log.class.php',
        'Main\Module'=>'Main/Module.class.php',
        'Main\route'=>'Main/route.class.php',
        'Main\session'=>'Main/session.class.php',
        'Main\sql'=>'Main/sql.class.php',
        'Main\template'=>'Main/template.class.php',
        'Main\Exception'=>'Main/Exception.class.php',

        'QRcode'    =>'Main/Support/Image/QRcode.class.php',
    );
    // 查看预存的class引用路径
    public static function showMap(){
        var_export(self::$obj_map);
    }
    /**
     * 注册自定义的类引入
     * @param $class 注册的类名
     * @param $dir   require 路径(相对路径)
     */
    public static function putobj($class, $dir){
        if(isset(self::$obj_map[$class])) throw new Exception($class.'已被注册!');
        self::$obj_map[$class] = $dir;
    }
    /**
     * @param $class
     * @param bool|true $app 当$class为Contr或Module时,代表所指向的APP,默认当前APP;
     *                        当$class为其他引用类时,其他参数生效,$app默认为true,代表单例模式实例化;
     * @param $pars 其他参数, 在new非Contr或Module对象时的参数, 注:单例模式下,显然只有第一次实例化时,参数才会被使用!
     * @return mixed 对象
     */
    public static function get($class, $app=true, $pars=null){
        if(strtolower($class) === 'module') return Module::getins();
        else if(self::checkClass($class)){
            $app = ( $app===true ) ? APP : $app;
            $s = '\\'.$app.'\\'.$class;
            return $s::getins();
        }else return self::getins($class, $app, $pars);
    }
    // 自动引入
    public static function requireClass($class){
        if(isset(self::$obj_map[$class])) self::includeWithException(ROOT.self::$obj_map[$class]);
        else {
            if( ($is = strrpos($class, '\\')) !== false) {
                $app = substr($class, 0 ,(int)$is);
                $classname = substr($class, (int)$is+1);
            }else $classname = $class;
            if (strtolower(substr($class, -5)) == 'contr')  self::includeWithException(ROOT.'Application/'.$app.'/Controller/'.$classname.'.class.php');
            else if (strtolower(substr($class, -6)) == 'module')  self::includeWithException(ROOT.'Application/'.$app.'/Module/'.$classname.'.class.php');
            else if (strtolower(substr($class, -3)) == 'obj')  self::includeWithException(ROOT.'Application/'.$app.'/Object/'.$classname.'.class.php');
            else if (strtolower(substr($class, -4))=='tool')  self::includeWithException(ROOT . 'tool/'. $classname . '.class.php');
            else  {
                $parameter = explode('\\', $class);
                $str = '';
                foreach ($parameter as $k=>$v){
                    $str .= $str ? '/'.$v : $v;
                }
                $str0 = ROOT.'Main/'.$str.'.class.php';
                $str = ROOT.$str.'.class.php';
                if(file_exists($str0))  self::includeWithException($str0);
                else if(file_exists($str))  self::includeWithException($str);
                else  self::includeWithException(ROOT . 'Include/' . $classname . '.class.php');
            }
        }
    }
    // 异常处理
    private static function includeWithException($where){
        try{
            if(file_exists($where)) require $where;
            else throw new Exception('引入文件 '.$where.' 不存在! ',99);
        }catch(Exception $e){
            if(ini_get('display_errors')) echo $e->getMessage();
            exit;
        }
    }
    // 判断 $class 类型
    private static function checkClass($class){
        if(strtolower(substr($class, -5))=='contr' || strtolower(substr($class, -6))=='module' || strtolower(substr($class, -3))=='obj') return true;
        else return false;
    }
    // 缓存其他 class 的单例并返回实例
    private static function getins($class, $singleton=true, $par=null){
        if(!class_exists($class)) throw new Exception($class.'不存在!');
        $parstr ='' ;
        if($par !== null){
            $par = array_values($par);
            for($i = 0 ; $i < count($par) ; $i++){
                $parstr .= ',$par['.$i.']';
            }
            $parstr = ltrim($parstr, ',');
        }
        $str = 'new $class('.$parstr.');';
        if($singleton===true){
            $str = 'self::$obj_ins[$class] = '. $str;
            if(!isset(self::$obj_ins[$class]) && empty(self::$obj_ins[$class]))  eval($str);
            return self::$obj_ins[$class];
        }else {
            $str = '$cache = '. $str;
            eval($str);
            return $cache;
        }
    }
}
spl_autoload_register(array('Main\obj', 'requireClass'));