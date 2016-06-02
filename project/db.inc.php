<?php
defined('IN_SYS')||exit('ACC Denied');
return  <<<EEE
set names utf8;
SET FOREIGN_KEY_CHECKS=0;
CREATE TABLE `hk_user_account` (
  `id` bigint(1) NOT NULL AUTO_INCREMENT,
  `account` char(15) NOT NULL DEFAULT '' COMMENT '账号',
  `passwd` char(15) NOT NULL DEFAULT '' COMMENT '密码',
  `name` varchar(20)  NOT NULL  DEFAULT '' COMMENT '此id的姓名',
  `sex` tinyint(1) NOT NULL DEFAULT 1 COMMENT '此id性别',
  `img` varchar(200)  DEFAULT '' COMMENT '此id的头像地址',
  `sign` varchar(200)  DEFAULT '' COMMENT '个性签名',
  `tel` bigint(1) DEFAULT 0 COMMENT '此id的移动电话',
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
  UNIQUE KEY (`idcard`),
  UNIQUE KEY (`tel`),
  UNIQUE KEY (`account`)
) ENGINE=innodb  AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
CREATE TABLE `hk_user_qq` (
  `id` bigint(1) NOT NULL AUTO_INCREMENT,
  `openid` char(50)  NOT NULL DEFAULT '' COMMENT 'QQ用户标识',
  `name` varchar(20)  NOT NULL DEFAULT '' COMMENT '此QQ用户的姓名',
  `sex` tinyint(1) NOT NULL DEFAULT 1 COMMENT '此QQ用户性别',
  `img` varchar(200)  DEFAULT '' COMMENT '此QQ用户的头像地址',
  `account_id` int(1)  DEFAULT '' COMMENT '此QQ用户关联的平台用户id,ref:hk_user_account->id',
  PRIMARY KEY (`id`),
  UNIQUE KEY (`account_id`),
  UNIQUE KEY (`openid`)
) ENGINE=innodb  AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

EEE;
