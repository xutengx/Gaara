<?php
defined('IN_SYS')|| define('IN_SYS', substr(str_replace('\\','/',__FILE__),strrpos(str_replace('\\','/',__FILE__),'/')+1));
if(!isset($_GET[MD5(IN_SYS)])||empty($_GET[MD5(IN_SYS)])) $_GET[MD5(IN_SYS)] = 'index/webScoket/indexDo/';
require 'init.php';