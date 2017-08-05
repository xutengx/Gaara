<?php

namespace Main\Core;

defined('IN_SYS') || exit('ACC Denied');

/**
 * 原 Loader
 * 享元模式实现,所有对象单例
 */
class Integrator {

    // 缓存对象,实现单元素模式
    private static $obj_ins = array();
    // 预存的class引用路径
    private static $obj_map = array(
        'HTMLPurifier' => 'Main/Support/Secure/htmlpurifier-4.7.0/library/HTMLPurifier.includes.php',
        'QRcode' => 'Main/Support/Image/QRcode.php',
    );

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

    // 自动引入
    public static function requireClass($class) {
        $path = ROOT . str_replace('\\', '/', $class) . '.php';
        // 根据预存的class引用路径
        if (isset(self::$obj_map[$class]))
            self::includeFile(ROOT . self::$obj_map[$class]);
        else
            self::includeFile($path);
    }

    private static function includeFile($where) {
        if (file_exists($where)) {
            require $where;
        }
        return true;
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
    private static function getins($class, $par = []) {
        if (!class_exists($class))
            throw new Exception('实例化类 : ' . $class . ' 不存在!', 99);
        return isset(self::$obj_ins[$class]) ? self::$obj_ins[$class] : self::$obj_ins[$class] = new $class(...$par);
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

// 注册加载
spl_autoload_register(array(Integrator::class, 'requireClass'));
// 引入别名类
if (file_exists(ROOT . 'Main/Conf/ClassAilas.php'))
    require (ROOT . 'Main/Conf/ClassAilas.php');

