<?php
namespace App\mysql\Contr;
defined('IN_SYS')||exit('ACC Denied');
class test extends \Main\Core\Container {
    protected static $instance = \App\mysql\Model\visitorInfoModel::class; 
}