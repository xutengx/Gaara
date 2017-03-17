<?php
//phpinfo();exit;
// 性能更加~!
defined('IN_SYS')|| define('IN_SYS', substr(str_replace('\\','/',__FILE__),strrpos(str_replace('\\','/',__FILE__),'/')+1));
if(!isset($_GET[MD5(IN_SYS)])||empty($_GET[MD5(IN_SYS)])) $_GET[MD5(IN_SYS)] = 'admin/index/indexDo';
require './init.php';

