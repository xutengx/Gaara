<?php
namespace Main\Core\Controller;
use \Main\Core\Controller\Module;
defined('IN_SYS')||exit('ACC Denied');
/**
 * 响应页面
 * Class HttpController
 * @package Main\Core\Controller
 */
abstract class HttpController extends \Main\Core\Controller{
    /**
     * 页面赋值,接取页面参数
     */
    use Module\ViewModule;

    /**
     * BD事务
     */
//    use Module\TransactionModule;

    /**
     * 微信授权
     */
    use Module\WechatModule;

    /**
     * 设置Cookie
     */
    use Module\CookieModule;

    /**
     * trait 构造函数队列
     * @var array
     */
    protected $__construct = [
        'ViewModule',
//        'TransactionModule',
        'WechatModule',
        'CookieModule'
    ];
}