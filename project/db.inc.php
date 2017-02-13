<?php
defined('IN_SYS')||exit('ACC Denied');
return  <<<EEE
set names utf8;
SET FOREIGN_KEY_CHECKS=0;
CREATE TABLE `file_app` (
  `id` bigint(1) NOT NULL AUTO_INCREMENT,
  `app_id` bigint(1) NOT NULL DEFAULT 0 COMMENT '接入系统的应用id',
  `user_id` bigint(1) NOT NULL DEFAULT 0 COMMENT '应用id下的用户id',
  `truename` varchar(20) DEFAULT '' COMMENT '此id的姓名',
  `sex` tinyint(1) unsigned DEFAULT '0' COMMENT '此id性别,0为女',
  `img` varchar(200) DEFAULT '' COMMENT '此id的头像地址',
  `tel` varchar(20) DEFAULT '' COMMENT '此id的电话',
  `time` datetime COMMENT '最近效验时间',
  PRIMARY KEY (`ID`),
  UNIQUE KEY (`openid`)
) ENGINE=innodb AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

EEE;
