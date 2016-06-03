<?php
// 有问题请联系 QQ 1771033392
$GLOBALS['statistic']=array(
    '_beginTime'=>microtime( true ),
    '_beginMemory'=>memory_get_usage() );

defined('IN_SYS')|| define('IN_SYS', substr(str_replace('\\','/',__FILE__),strrpos(str_replace('\\','/',__FILE__),'/')+1));
defined('ROOT')||define('ROOT',str_replace('\\','/',dirname(__FILE__)).'/');

// 自动加载类
require (ROOT . 'Main/Core/Loader.class.php');
// 公用方法
require (ROOT . 'Main/Core/func.class.php');
// 执行
\Main\Core\Route::Start();

