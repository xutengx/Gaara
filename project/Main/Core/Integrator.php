<?php

namespace Main\Core;

defined('IN_SYS') || exit('ACC Denied');

/**
 * 享元模式,所有对象单例
 * 依赖注入
 */
class Integrator {

    // 缓存对象,实现单元素模式
    private static $obj_ins = array();

    /**
     * 通过全局obj()调用
     * @param string    $class      类名(支持别名)
     * @param array     $pars       new一个对象所需要的参数; 注:单例模式下,显然只有第一次实例化时,参数才会被使用!
     *
     * @return objtect
     */
    public static function get($class = '', array $pars = array()) {
        $class = str_replace('/', '\\', $class);
        // 别名修正
        $class = self::checkAlias($class);
        // 返回对象
        return self::getins($class, $pars);
    }

    /**
     * Route中实现自动依赖注入, 避免别名干扰
     * @param string    $class      类名(支持别名)
     * @param array     $pars       new一个对象所需要的参数; 注:单例模式下,显然只有第一次实例化时,参数才会被使用!
     *
     * @return objtect
     */
    public static function getWithoutAlias($class = '', array $pars = array()) {
        $class = str_replace('/', '\\', $class);
        // 返回对象
        return self::getins($class, $pars);
    }

    /**
     * 根命名空间的类别名, 使用别名的类均继承 \Main\Core\Container::class
     * @param string $className
     * @return string
     */
    private static function checkAlias($className) {
        $test = new \ReflectionClass($className);
        $fatherClass = $test->getParentClass();
        if ($fatherClass !== false && $fatherClass->name === \Main\Core\Container::class) {
            return $className::getInstanceName();
        } else
            return $className;
    }

    /**
     * 返回缓存的单例
     * @param string     $class     完整类名
     * @param null|array $par       参数数组
     *
     * @return mixed
     * @throws Exception
     */
    private static function getins($class, $par = []) {
        if (!class_exists($class))
            throw new Exception('实例化类 : ' . $class . ' 不存在!', 99);
//        return isset(self::$obj_ins[$class]) ? self::$obj_ins[$class] : self::$obj_ins[$class] = new $class(...$par);
        return isset(self::$obj_ins[$class]) ? self::$obj_ins[$class] : self::$obj_ins[$class] = self::getInstance($class, $par);
    }
    /**
     * 构造依赖注入
     * @param string    $className  类名
     * @param array     $par        实参数组
     * @return object
     */
    private static function getInstance(string $className,array $par = []) {
        $paramArr = self::getMethodParams($className, $par);
        return (new \ReflectionClass($className))->newInstanceArgs($paramArr);
    }
    
    /**
     * 方法依赖注入, 执行
     * @param string|object    $className  类名|对象
     * @param string    $methodName 方法名
     * @param array     $par        实参数组
     * @return mix
     */
    public static function run($className, string $methodName, array $params = []) {
        if(is_string($className)){
            // 获取类的实例, 如你所见没有传入构造的自定参数, 也就是说在 $className 第一次实例化,且依赖一个没有默认值的参数时,将会出错, 不过这种情况不常见
            $instance = self::getins($className, []);
        }elseif(is_object($className)){
            $instance = $className;
        }
        // 获取该方法所需要依赖注入的参数
        $paramArr = self::getMethodParams($className, $params, $methodName);
        return $instance->{$methodName}(...$paramArr);
    }

    /**
     * 获得类的方法参数，只获得有类型的参数
     * @param  string|object   $className      类名|对象
     * @param  array    $pars           调用者传入的参数,组成的数组
     * @param  string   $methodsName    调用的方法名
     * @return array    实参数组
     */
    private static function getMethodParams($className, array $pars = [], string $methodsName = '__construct'):array {
        // 通过反射获得该类
        $class = new \ReflectionClass($className);
        $paramArr = []; // 记录参数，和参数类型
        // 判断该类是否有构造函数
        if ($class->hasMethod($methodsName)) {
            // 获得构造函数
            $construct = $class->getMethod($methodsName);
            // 判断构造函数是否有参数
            $params = $construct->getParameters();
            if (count($params) > 0) {
                // 遍历所有形参
                foreach ($params as $param) {
                    // 表示符
                    $keyLock = true;
                    // 判断形参类型 是类
                    if ($paramClass = $param->getClass()) {
                        // 获得形参类名称
                        $paramClassName = $paramClass->getName();
                        // 如果存在'对应实例'于手动提供的参数数组中, 则使用一次
                        foreach($pars as $k => $obj){
                            if(is_object($obj) && $obj instanceof $paramClassName){
                                $paramArr[] = $obj;
                                unset($pars[$k]);
                                $keyLock = false;
                                break;
                            }
                        }
                        // 不存在'对应实例'于手动提供的参数数组中, 则递归注入
                        if($keyLock){
                            // 加入对象到参数列表
                            $paramArr[] = Integrator::getWithoutAlias($paramClassName);
                        }
                    }
                    // 判断形参类型 不是类
                    else {
                        // 存在普通参数
                        if (!empty($pars)) {
                            foreach ($pars as $k => $paramString) {
                                $paramArr[] = $paramString;
                                unset($pars[$k]);
                                break;
                            }
                        } 
                        // 不存在普通参数, 则尝试其默认值
                        elseif ($param->isDefaultValueAvailable()) {
                            $paramArr[] = $param->getDefaultValue();
                        }
                    }
                }
            }
        }
        return $paramArr;
    }
    
    
    // 查看预存的class引用路径
    public static function showMap() {
        var_export(self::$obj_map);
    }

    /**
     *  清除所有对象缓存
     *  用于隔离子进程之间的资源句柄
     */
    public static function unsetAllObj() {
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
    public static function putobj($class = '', $dir = '') {
        if (isset(self::$obj_map[$class]))
            throw new Exception($class . '已被注册!');
        self::$obj_map[$class] = $dir;
    }
}

