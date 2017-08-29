<?php
// 微信授权,专用入口
// test
define('IN_SYS', substr(str_replace('\\','/',__FILE__),strrpos(str_replace('\\','/',__FILE__),'/')+1));
if(  (!isset($_GET[MD5(IN_SYS)])||empty($_GET[MD5(IN_SYS)])) && isset($_GET['code']) && !empty($_GET['code'])){
    $_GET[MD5(IN_SYS)] = 'index/login/getInfoOnWechatProfessional/';
}
else exit('非法访问!');
include './index.php';