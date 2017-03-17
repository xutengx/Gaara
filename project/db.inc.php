<?php
defined('IN_SYS')||exit('ACC Denied');
return  <<<EEE
set names utf8;
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `balloon_admin`;
CREATE TABLE `balloon_admin` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `img` varchar(200)  NOT NULL DEFAULT '' COMMENT '管理员头像',
  `username` varchar(50)  NOT NULL DEFAULT '' COMMENT '管理员登入名',
  `nickname` varchar(20)  NOT NULL DEFAULT '管理员' COMMENT '管理员别名',
  `passwd` char(32)  NOT NULL DEFAULT '' COMMENT '登入密码',
  `last_login_time` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` char(15) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '管理员状态, 1,启用,0,禁用',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '管理员级别,1为最高级别',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`)
) ENGINE=innodb AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='管理员表';

INSERT INTO `balloon_admin` VALUES ('1', '', 'admin', 'xT', '4297f44b13955235245b2497399d7a93', '0', '0', '0', '1','1');

DROP TABLE IF EXISTS `balloon_category`;
CREATE TABLE `balloon_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(30) NOT NULL COMMENT '标志',
  `pid` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `create_time` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '数据状态, 1,可见,0,删除,2,后台可见',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `pid` (`pid`)
) ENGINE=innodb AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='分类表';

INSERT INTO `balloon_category` VALUES ('1', 'ARTICLE 管理', '0', '0', '0', '0', '1');
INSERT INTO `balloon_category` VALUES ('2', 'NOTICE 管理', '0', '0', '0', '0', '1');
INSERT INTO `balloon_category` VALUES ('3', '第一类文章', '1', '0', '0', '0', '1');
INSERT INTO `balloon_category` VALUES ('4', '第二类文章', '1', '0', '0', '0', '1');
INSERT INTO `balloon_category` VALUES ('5', '第三类文章', '1', '0', '0', '0', '1');
INSERT INTO `balloon_category` VALUES ('6', '第四类文章', '1', '0', '0', '0', '1');
INSERT INTO `balloon_category` VALUES ('7', '第一类公告', '2', '0', '0', '0', '1');
INSERT INTO `balloon_category` VALUES ('8', '第二类公告', '2', '0', '0', '0', '1');
INSERT INTO `balloon_category` VALUES ('9', '第三类公告', '2', '0', '0', '0', '1');
INSERT INTO `balloon_category` VALUES ('10', '第四类公告', '2', '0', '0', '0', '1');

EEE;
