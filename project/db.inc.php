<?php
defined('IN_SYS')||exit('ACC Denied');
return  <<<EEE
set names utf8;
SET FOREIGN_KEY_CHECKS=0;
CREATE TABLE `hk_user` (
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
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '此id状态,1:启用,0:禁用',
  `timeCreate` datetime COMMENT '注册时间',
  `timeLogin` datetime COMMENT '最近登入时间',
  `ipLogin` char(20) NOT NULL DEFAULT '' COMMENT '上次登入ip',
  `level` tinyint(1) unsigned DEFAULT 0 COMMENT '权限级别',
  PRIMARY KEY (`id`),
  INDEX tel (tel),
  INDEX email (email),
  INDEX idcard (idcard),
  UNIQUE KEY (`account`)
) ENGINE=innodb  AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

CREATE TABLE `hk_userqq` (
  `id` bigint(1) NOT NULL AUTO_INCREMENT,
  `openid` char(50)  NOT NULL DEFAULT '' COMMENT 'QQ用户标识',
  `name` varchar(20)  NOT NULL DEFAULT '' COMMENT '此QQ用户的姓名',
  `sex` tinyint(1) NOT NULL DEFAULT 1 COMMENT '此QQ用户性别',
  `img` varchar(200)  DEFAULT '' COMMENT '此QQ用户的头像地址',
  `account_id` bigint(1) COMMENT '此QQ用户关联的平台用户id,ref:hk_user_account->id',
  PRIMARY KEY (`id`),
  UNIQUE KEY (`account_id`),
  UNIQUE KEY (`openid`)
) ENGINE=innodb  AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

CREATE TABLE `hk_message` (
  `id` bigint(1) NOT NULL AUTO_INCREMENT,
  `form_id` bigint(1) NOT NULL DEFAULT 0 COMMENT '消息发送者id,ref:hk_user_account->id',
  `to_id` bigint(1) NOT NULL DEFAULT 0 COMMENT '消息接受者id,ref:hk_user_account->id',
  `content` varchar(2000)  NOT NULL DEFAULT '' COMMENT '消息正文,引用地址',
  `state` tinyint(1) NOT NULL DEFAULT 0 COMMENT '消息状态,0:草稿,1:已发送,2:送达,3:已读,4:发送失败,5:撤销',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '消息类型,0:普通文本,1:图片,2:视频,3:语音',
  `time` datetime COMMENT '消息时间',
  PRIMARY KEY (`id`),
  INDEX form_to_id (form_id,to_id)
) ENGINE=innodb  AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


EEE;
