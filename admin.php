<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/4 0004
 * Time: 10:43
 */
//ini_set('display_errors', 1);
define('IN_SYS', substr(str_replace('\\','/',__FILE__),strrpos(str_replace('\\','/',__FILE__),'/')+1));
if(!isset($_GET[MD5(IN_SYS)])||empty($_GET[MD5(IN_SYS)])) $_GET[MD5(IN_SYS)] = 'admin/index/indexDo';
include './index.php';