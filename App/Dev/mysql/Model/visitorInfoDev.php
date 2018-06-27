<?php

namespace App\Dev\mysql\Model;

use Gaara\Core\Model\QueryBuiler;

class test extends \Gaara\Core\Model {

    protected $table = 'visitor_info';

    protected $connection = 'con3';


    public function construct() {
    }

    protected $create_sql = <<<SQL
CREATE TABLE `visitor_info` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名or联系人',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `scene` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '接入场景',
  `TEst` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(500) DEFAULT NULL COMMENT '需求说明',
  `created_at` datetime NOT NULL DEFAULT '1111-11-11 11:11:11' COMMENT '新增时间',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  `is_del` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23630 DEFAULT CHARSET=utf8 COMMENT='访客登记表';
SQL;

}

class visitorInfoDev extends test{

    public function registerMethodForQueryBuiler():array{
        return [
            'ID_is_bigger_than_1770' => function(QueryBuiler $queryBuiler): QueryBuiler{
                return $queryBuiler->where('id','>','1770');
            },
            'ID_rule' => function(QueryBuiler $queryBuiler, $id = 1800): QueryBuiler{
                return $queryBuiler->ID_is_bigger_than_1770()->where('id','<',$id);
            },
        ];
    }

}
