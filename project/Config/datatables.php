<?php
defined('IN_SYS')||exit('ACC Denied');
return  <<<EEE
set names utf8;
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `main_user`;
CREATE TABLE `main_user` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `passwd` varchar(255) NOT NULL DEFAULT '' COMMENT '登入密码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1.启用 2.禁用',
  `last_login_ip` int(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录IP, INET_ATON',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '新增时间',
  `last_login_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后在线时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=innodb AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='用户表';

INSERT INTO `main_user` VALUES ('1', 'admin@163.com', '$2y$10$1T62akHp47oLeIKuv6DzU.ZLnjXycsUlvAjF.m6dBi0XgPYhICF8q', '1', '3232235814', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);


EEE;
