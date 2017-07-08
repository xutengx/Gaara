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
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '管理员状态, 1,启用,0,禁用',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '管理员级别,1为最高级别',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '新增时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
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

DROP TABLE IF EXISTS `balloon_article`;
CREATE TABLE `balloon_article` (
  `id` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '文章内容',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `bookmark` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `pid` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型文章表';

INSERT INTO `balloon_article` VALUES ('1', '0', '<h1>\r\n	OneThink1.1开发版发布&nbsp;\r\n</h1>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong>OneThink是一个开源的内容管理框架，基于最新的ThinkPHP3.2版本开发，提供更方便、更安全的WEB应用开发体验，采用了全新的架构设计和命名空间机制，融合了模块化、驱动化和插件化的设计理念于一体，开启了国内WEB应用傻瓜式开发的新潮流。&nbsp;</strong> \r\n</p>\r\n<h2>\r\n	主要特性：\r\n</h2>\r\n<p>\r\n	1. 基于ThinkPHP最新3.2版本。\r\n</p>\r\n<p>\r\n	2. 模块化：全新的架构和模块化的开发机制，便于灵活扩展和二次开发。&nbsp;\r\n</p>\r\n<p>\r\n	3. 文档模型/分类体系：通过和文档模型绑定，以及不同的文档类型，不同分类可以实现差异化的功能，轻松实现诸如资讯、下载、讨论和图片等功能。\r\n</p>\r\n<p>\r\n	4. 开源免费：OneThink遵循Apache2开源协议,免费提供使用。&nbsp;\r\n</p>\r\n<p>\r\n	5. 用户行为：支持自定义用户行为，可以对单个用户或者群体用户的行为进行记录及分享，为您的运营决策提供有效参考数据。\r\n</p>\r\n<p>\r\n	6. 云端部署：通过驱动的方式可以轻松支持平台的部署，让您的网站无缝迁移，内置已经支持SAE和BAE3.0。\r\n</p>\r\n<p>\r\n	7. 云服务支持：即将启动支持云存储、云安全、云过滤和云统计等服务，更多贴心的服务让您的网站更安心。\r\n</p>\r\n<p>\r\n	8. 安全稳健：提供稳健的安全策略，包括备份恢复、容错、防止恶意攻击登录，网页防篡改等多项安全管理功能，保证系统安全，可靠、稳定的运行。&nbsp;\r\n</p>\r\n<p>\r\n	9. 应用仓库：官方应用仓库拥有大量来自第三方插件和应用模块、模板主题，有众多来自开源社区的贡献，让您的网站“One”美无缺。&nbsp;\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong>&nbsp;OneThink集成了一个完善的后台管理体系和前台模板标签系统，让你轻松管理数据和进行前台网站的标签式开发。&nbsp;</strong> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<h2>\r\n	后台主要功能：\r\n</h2>\r\n<p>\r\n	1. 用户Passport系统\r\n</p>\r\n<p>\r\n	2. 配置管理系统&nbsp;\r\n</p>\r\n<p>\r\n	3. 权限控制系统\r\n</p>\r\n<p>\r\n	4. 后台建模系统&nbsp;\r\n</p>\r\n<p>\r\n	5. 多级分类系统&nbsp;\r\n</p>\r\n<p>\r\n	6. 用户行为系统&nbsp;\r\n</p>\r\n<p>\r\n	7. 钩子和插件系统\r\n</p>\r\n<p>\r\n	8. 系统日志系统&nbsp;\r\n</p>\r\n<p>\r\n	9. 数据备份和还原\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	&nbsp;[ 官方下载：&nbsp;<a href=\"http://www.onethink.cn/download.html\" target=\"_blank\">http://www.onethink.cn/download.html</a>&nbsp;&nbsp;开发手册：<a href=\"http://document.onethink.cn/\" target=\"_blank\">http://document.onethink.cn/</a>&nbsp;]&nbsp;\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong>OneThink开发团队 2013~2014</strong> \r\n</p>', '', '0');

DROP TABLE IF EXISTS `visitor_info`;
CREATE TABLE `visitor_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名or联系人',
  `phone` int(1) unsigned NOT NULL DEFAULT 0 COMMENT '手机号',
  `scene` int(1) unsigned NOT NULL DEFAULT 0 COMMENT '接入场景',
  `note` varchar(500) NOT NULL DEFAULT '' COMMENT '需求说明',
  `created_at` int(11) NOT NULL DEFAULT 0 COMMENT '新增时间',
  `updated_at` int(11) NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=innodb AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='访客登记表';
EEE;
