<?php
// 有问题请联系 QQ 68822684
$GLOBALS['statistic'] = array(
    '_beginTime' => microtime(true),
    '_beginMemory' => memory_get_usage()
);
// 入口文件名, 应该与nginx执行脚本保持一致 eg:index.php 
defined('IN_SYS') || define('IN_SYS', substr(str_replace('\\','/',__FILE__),strrpos(str_replace('\\','/',__FILE__),'/')+1));

// 入口文件目录在服务器的绝对路径 eg:/mnt/hgfs/www/git/php_/project/ 
define('ROOT', str_replace('\\','/',dirname(__FILE__)).'/');

/**
 * 是否命令模式 eg:true 
 * 已知使用者: \Main\Core\Route::getRouteType()
 */
define('CLI', (php_sapi_name() !== 'cli') ? false : true);

// 网路根地址 eg:http://192.168.43.128/git/php_/project/
define('HOST', ( isset($_SERVER['HTTP_HTTPS']) ? $_SERVER['HTTP_HTTPS'] : $_SERVER['REQUEST_SCHEME'] ) . '://' . $_SERVER['HTTP_HOST'] . str_replace(IN_SYS, '', $_SERVER['SCRIPT_NAME']));

/**
 * 配置文件存放路径 eg:/mnt/hgfs/www/git/php_/project/Config/
 * 已知使用文件: config.inc.php
 */
define('CONFIG', ROOT.'Config/');

/**
 * 路由文件存放路径 eg:/mnt/hgfs/www/git/php_/project/Route/
 * 已知使用者: \Main\Core\Route::getRouteRule()
 */
define('ROUTE', ROOT.'Route/');

/**
 * 自动加载
 */
require (ROOT . 'vendor/autoload.php');

/**
 * 申明debug ( 读取配置 )
 */
define('DEBUG', obj(Conf::class)->app['debug']);

/**
 * 执行
 */
\Main\Core\Route::Start();