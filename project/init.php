<?php
defined('IN_SYS')|| define('IN_SYS', substr(str_replace('\\','/',__FILE__),strrpos(str_replace('\\','/',__FILE__),'/')+1));
defined('ROOT')||define('ROOT',str_replace('\\','/',dirname(__FILE__)).'/');

//自动加载类
require (ROOT . 'Main/loader.class.php');
//公用方法
require (ROOT . 'Main/func.class.php');

\Main\route::Start();