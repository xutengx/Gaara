<?php
namespace Main\Core\Controller;
use \Main\Core\Controller\Module;
defined('IN_SYS')||exit('ACC Denied');
/**
 * RESTful API 控制器类
 */
abstract class RestController extends \Main\Core\Controller{
    /**
     * 根据APi请求类型, 执行对应操作,并返回指定格式数据
     */
    use Module\RestModule;
    /**
     * trait 构造函数队列
     * @var array
     */
    protected $__construct = [
        'RestModule'
    ];
}
