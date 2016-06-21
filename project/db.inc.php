<?php
defined('IN_SYS')||exit('ACC Denied');
return  <<<EEE
set names utf8;
SET FOREIGN_KEY_CHECKS=0;
CREATE TABLE `mq_user` (
  `id` bigint(1) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `name` varchar(20) DEFAULT '' COMMENT '此id的昵称',
  `truename` varchar(20) DEFAULT '' COMMENT '此id的姓名',
  `sex` tinyint(1) unsigned DEFAULT '0' COMMENT '此id性别,0为女',
  `img` varchar(200) DEFAULT '' COMMENT '此id的头像地址',
  `tel` varchar(20) DEFAULT '' COMMENT '此id的电话',
  `passwd` char(32) DEFAULT '' COMMENT '以电话登入时的密码,md5',
  `code` int(1) DEFAULT 0 COMMENT '短信验证码',
  `time` datetime COMMENT '最近效验时间',
  PRIMARY KEY (`ID`),
  INDEX tel (`tel`),
  INDEX openid (`openid`)
) ENGINE=innodb AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

EEE;
