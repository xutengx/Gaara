<?php

return <<<EEE
set names utf8;
SET FOREIGN_KEY_CHECKS=0;

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
INSERT INTO `main_user` VALUES ('100', '1771033392@qq.com', '$2y$10$1T62akHp47oLeIKuv6DzU.ZLnjXycsUlvAjF.m6dBi0XgPYhICF8q', '', '1', '1', '3232246529', '2017-09-27 14:48:21', '2017-09-13 11:37:43', '2017-09-27 14:48:21');


CREATE TABLE `main_admin` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `username` varchar(100) NOT NULL DEFAULT '' COMMENT '管理员登入名',
  `passwd` varchar(255) NOT NULL DEFAULT '' COMMENT '登入密码',
  `last_login_ip` int(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录IP, INET_ATON',
  `last_login_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后在线时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '新增时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '数据更新时间',
  `create_admin_id` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '此管理员的创建者ID, main_admin.id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=innodb AUTO_INCREMENT=2000000000 DEFAULT CHARSET=utf8 COMMENT='管理员表';

INSERT INTO `main_admin` VALUES ('2000000000', 'admin', '$2y$10$1T62akHp47oLeIKuv6DzU.ZLnjXycsUlvAjF.m6dBi0XgPYhICF8q', '3232235814', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP,'0');

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

CREATE TABLE `user_application` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '应用ID',
  `merchant_id` int(1) unsigned NOT NULL COMMENT '商户ID,user_merchant.id, 既是用户ID, main_user.id',
  `application_name` varchar(100) NOT NULL DEFAULT '' COMMENT '应用名称',
  `describe` varchar(250) NOT NULL DEFAULT '' COMMENT '应用描述',
  `application_logo_file` varchar(100) NOT NULL DEFAULT '' COMMENT '应用logo文件',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '资料提交时间',
  `modify_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '资料修改时间',
  PRIMARY KEY (`id`),
  KEY `merchant_id` (`merchant_id`)
) ENGINE=innodb AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='应用信息表';

CREATE TABLE `merchant_secret` (
  `id` int(1) unsigned NOT NULL COMMENT '商户ID,user_merchant.id',
  `yh_key` char(32) NOT NULL DEFAULT '' COMMENT '32位随机字符',
  `public_key` varchar(2048) NOT NULL COMMENT '公钥',
  `private_key` varchar(2048) NOT NULL COMMENT '私钥',
  PRIMARY KEY (`id`)
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='商户密钥表';

CREATE TABLE `payment_type` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `describe` varchar(100) NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=innodb AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='支付场景表';

INSERT INTO `payment_type` VALUES (1,'微信公众号支付 JSAPI');

CREATE TABLE `passageway_wechat` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `application_id` int(1) unsigned NOT NULL COMMENT '应用ID,user_application.id',
  PRIMARY KEY (`id`)
) ENGINE=innodb AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='微信配置信息表';

CREATE TABLE `passageway_fuiou` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `application_id` int(1) unsigned NOT NULL COMMENT '应用ID,user_application.id',
  `merchant_id` int(1) unsigned NOT NULL COMMENT '商户ID,user_merchant.id',
  `payment_type_id` int(1) unsigned NOT NULL COMMENT '支付场景ID, payment_type.id',
  `ins_cd` varchar(20) NOT NULL COMMENT '机构号,接入机构在富友的唯一代码',
  `mchnt_cd` varchar(15) NOT NULL COMMENT '商户号, 富友分配给二级商户的商户号',
  `public_key` varchar(2048) NOT NULL COMMENT '公钥',
  `peivate_key` varchar(2048) NOT NULL COMMENT '私钥',
  PRIMARY KEY (`id`)
) ENGINE=innodb AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='上海富友支付配置信息表';

CREATE TABLE `passageway_alipay` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `application_id` int(1) unsigned NOT NULL COMMENT '应用ID,user_application.id',
  PRIMARY KEY (`id`)
) ENGINE=innodb AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='支付宝配置信息表';

CREATE TABLE `payment_order` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `application_id` int(1) unsigned NOT NULL COMMENT '应用ID,user_application.id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '支付场景 1 微信公众号支付,2 ...',
  PRIMARY KEY (`id`)
) ENGINE=innodb AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='支付订单表';

CREATE TABLE `payment_order_process` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `application_id` int(1) unsigned NOT NULL COMMENT '应用ID,user_application.id',
  PRIMARY KEY (`id`)
) ENGINE=innodb DEFAULT CHARSET=utf8 COMMENT='支付订单流程表';

CREATE TABLE `notify_record` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '通知ID',
  `application_id` int(1) unsigned NOT NULL COMMENT '应用ID,user_application.id',
  PRIMARY KEY (`id`)
) ENGINE=innodb AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='异步通知记录表';

EEE;
