<?php
define('IN_SYS', substr(str_replace('\\','/',__FILE__),strrpos(str_replace('\\','/',__FILE__),'/')+1));
// 自定义入口用法
// $_GET[MD5(IN_SYS)] = 'admin/admin/indexDo';
include './index.php';