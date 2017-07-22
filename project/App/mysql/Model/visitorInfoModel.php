<?php

namespace App\mysql\Model;

defined('IN_SYS') || exit('ACC Denied');

class visitorInfoModel extends \Main\Core\Model {

    protected $table = 'visitor_info';
    
    protected $create_sql = <<<SQL
DROP TABLE IF EXISTS `visitor_info`;
CREATE TABLE `visitor_info` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名or联系人',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `scene` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '接入场景',
  `note` varchar(500) DEFAULT '' COMMENT '需求说明',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '新增时间',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='访客登记表';
SQL;

}
