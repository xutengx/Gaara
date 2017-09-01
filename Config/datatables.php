<?php

defined('IN_SYS') || exit('ACC Denied');
return <<<EEE
set names utf8;
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `main_user`;
CREATE TABLE `main_user` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `passwd` varchar(255) NOT NULL DEFAULT '' COMMENT '登入密码',
  `secret` varchar(255) NOT NULL DEFAULT '' COMMENT '调用支付api时生成sign所用的盐',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1.启用 2.禁用',
  `payment` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '是否可调用支付,1.启用 2.禁用',
  `last_login_ip` int(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录IP, INET_ATON',
  `last_login_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后在线时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '新增时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '数据更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=innodb AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='用户表';

INSERT INTO `main_user` VALUES ('1', 'admin@163.com', '$2y$10$1T62akHp47oLeIKuv6DzU.ZLnjXycsUlvAjF.m6dBi0XgPYhICF8q', '','1', '2', '3232235814', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

DROP TABLE IF EXISTS `main_admin`;
CREATE TABLE `main_admin` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `username` varchar(100) NOT NULL DEFAULT '' COMMENT '管理员登入名',
  `passwd` varchar(255) NOT NULL DEFAULT '' COMMENT '登入密码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可登入,1.启用 2.禁用',
  `last_login_ip` int(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录IP, INET_ATON',
  `last_login_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后在线时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '新增时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '数据更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=innodb AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='管理员表';

INSERT INTO `main_admin` VALUES ('1', 'admin', '$2y$10$1T62akHp47oLeIKuv6DzU.ZLnjXycsUlvAjF.m6dBi0XgPYhICF8q', '1', '3232235814', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

DROP TABLE IF EXISTS `user_merchant`;
CREATE TABLE `user_merchant` (
  `id` int(1) unsigned NOT NULL COMMENT '商户ID, 既是用户ID, main_user.id',
  `merchant_name` varchar(100) NOT NULL DEFAULT '' COMMENT '商户名称',
  `merchant_website` varchar(100) NOT NULL DEFAULT '' COMMENT '商户网址',
  `merchant_website_site_number` varchar(100) NOT NULL DEFAULT '' COMMENT '网址备案号',
  `organization_file` varchar(100) NOT NULL DEFAULT '' COMMENT '组织机构代码原件',
  `license_file` varchar(100) NOT NULL DEFAULT '' COMMENT '营业执照原件',
  `license_code` varchar(100) NOT NULL DEFAULT '' COMMENT '营业执照编码',
  `license_address` varchar(100) NOT NULL DEFAULT '' COMMENT '营业执照地址',
  `license_range` varchar(100) NOT NULL DEFAULT '' COMMENT '营业执照营业范围',
  `business_range` varchar(100) NOT NULL DEFAULT '' COMMENT '实际营业范围',
  `business_address` varchar(100) NOT NULL DEFAULT '' COMMENT '实际营业地址',
  `corporation_name` varchar(100) NOT NULL DEFAULT '' COMMENT '法人姓名',
  `corporation_country` varchar(100) NOT NULL DEFAULT '' COMMENT '法人国籍',
  `corporation_sex` tinyint(1) NOT NULL DEFAULT 1 COMMENT '法人性别,1 男,2 女',
  `corporation_identification_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '法人证件类型,1.身份证,2.军官证',
  `corporation_identification_number` varchar(100) NOT NULL DEFAULT '' COMMENT '法人证件号码',
  `corporation_identification_validity` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '法人证件有效期',
  `corporation_phone` varchar(100) NOT NULL DEFAULT '' COMMENT '法人联系方式',
  `authorizer_name` varchar(100) NOT NULL DEFAULT '' COMMENT '授权人姓名',
  `authorizer_country` varchar(100) NOT NULL DEFAULT '' COMMENT '授权人国籍',
  `authorizer_sex` tinyint(1) NOT NULL DEFAULT 1 COMMENT '授权人性别,1 男,2 女',
  `authorizer_identification_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '授权人证件类型,1.身份证,2.军官证',
  `authorizer_identification_number` varchar(100) NOT NULL DEFAULT '' COMMENT '授权人证件号码',
  `authorizer_identification_validity` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '授权人证件有效期',
  `authorizer_phone` varchar(100) NOT NULL DEFAULT '' COMMENT '授权人联系方式',
  `status` tinyint(1) NOT NULL DEFAULT 1  COMMENT '审核状态,1.未审核,2.审核通过,3.审核未通过',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '资料提交时间',
  `modify_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '资料修改时间',
  `reviewe_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '资料审核时间',
  `admin_id` int(1) unsigned NOT NULL DEFAULT 0 COMMENT '资料审核人id, main_admin.id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `merchant_name` (`merchant_name`)
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='商户信息表';

DROP TABLE IF EXISTS `merchant_secret`;
CREATE TABLE `merchant_secret` (
  `id` int(1) unsigned NOT NULL COMMENT '商户ID, 既是用户ID, main_user.id',
  PRIMARY KEY (`id`)
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='商户密钥表';

EEE;
