<?php
defined('IN_SYS')||exit('ACC Denied');

$_CFG = array();
$_CFG['path']= 'path';                      // 路由关键字      // 全局PATH常量
$_CFG['timezone']= 'PRC';                   // 时区
$_CFG['sessionPath']= 'data/Session';       // session存储路径 // 全局SESSIONPATH常量
$_CFG['sessionLife']= 3600*24*7;            // session 时效性  // 全局SESSIONLIFE常量


//$_CFG['appid']= 'wx996bd5d838d5d827';
//$_CFG['appsecret']= 'd3927177ebc315da18681dd9876ed073';
//$_CFG['host']= '10.4.17.219';
//$_CFG['user']= 'root';
//$_CFG['pwd']='Huawei$123#_';
//$_CFG['db'] = 'v5';

$_CFG['appid']= 'wx8f0ca1bc115c1fae';                           // 全局APPID常量
$_CFG['appsecret']= 'd4624c36b6795d1d99dcf0547af5443d';         // 全局APPSECRET常量
$_CFG['host']= '127.0.0.1';
$_CFG['user']= 'root';
$_CFG['pwd']='root';
$_CFG['db'] = 'Boke';

$_CFG['tablepre'] = 'boke_';
$_CFG['keytable'] = 'boke_user';
$_CFG['char'] = 'UTF8';
$_CFG['sql'] = <<<EEE
set names utf8;
SET FOREIGN_KEY_CHECKS=0;
CREATE TABLE `boke_article` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '默认标题' COMMENT '文章题目',
  `content` longtext NOT NULL  COMMENT '文章内容',
  `status` ENUM('草稿','公开','禁用','私有') NOT NULL DEFAULT '草稿' COMMENT '文章状态',
  `comment_status` ENUM('否','是') NOT NULL COMMENT '可否评论',
  `comment_num` int(1) unsigned NOT NULL DEFAULT 0 COMMENT '评论数量',
  `like_num` int(1) unsigned NOT NULL DEFAULT 0 COMMENT '点赞数量',
  `time_create` datetime COMMENT '文章建立时间',
  `time_modify` datetime COMMENT '文章最近修改时间',
  `userid` int(1) unsigned NOT NULL DEFAULT 0 COMMENT '用户id',
  `groupid` int(1) unsigned NOT NULL DEFAULT 0 COMMENT '分类id',
  PRIMARY KEY (`id`)
) ENGINE=innodb  AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
CREATE TABLE `boke_user` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `keyid` varchar(50)  NOT NULL DEFAULT '' COMMENT '用户标识,微信openid,pc游客浏览器id,等',
  `account` char(15)  NOT NULL  DEFAULT '0' COMMENT '账号',
  `passwd` char(15)  NOT NULL  DEFAULT '0' COMMENT '密码',
  `name` varchar(20)  NOT NULL  DEFAULT '' COMMENT '此id的姓名',
  `sex` ENUM('男','女','未设置')  NOT NULL DEFAULT '未设置' COMMENT '此id性别',
  `img` varchar(200)  DEFAULT '' COMMENT '此id的头像地址',
  `sign` varchar(200)  DEFAULT '' COMMENT '个性签名',
  `phone` char(12) DEFAULT '' COMMENT '此id的电话',
  `email` varchar(200) DEFAULT '' COMMENT '此id的邮箱',
  `idcard` char(20) DEFAULT '' COMMENT '此id的身份证',
  `address` varchar(200) DEFAULT '' COMMENT '此id的地址',
  `status` ENUM('禁用','活跃') NOT NULL DEFAULT '活跃' COMMENT '此id状态',
  `time_create` datetime COMMENT '注册时间',
  `time_lastlogin` datetime COMMENT '最近登入时间',
  `ip_lastlogin` char(20) NOT NULL DEFAULT '' COMMENT '上次登入ip',
  `login_status` bigint(1) unsigned NOT NULL DEFAULT 0 COMMENT '登录锁',
  `level` tinyint(1) unsigned DEFAULT '0' COMMENT '权限级别',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=innodb  AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

EEE;
