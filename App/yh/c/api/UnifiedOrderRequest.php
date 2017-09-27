<?php

declare(strict_types = 1);
namespace App\yh\c\api;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m\UserApplication;
use Main\Core\Request;
use Main\Core\Controller\HttpController;
use PDOException;

/**
 * 统一下单接口
 */
class UnifiedOrderRequest extends HttpController {
    // 请求的过来的参数
    private $param = [];
    // 使用的支付场景
    private $type = null;
    // 使用的上游通道
    private $passageway = null;
    // 商户的应用在yh存储的对应通道的对接资料
    private $info = [];
    // 上游的唯一订单号
    private $passagewayOrderNo = null;
    // yh的唯一订单号
    private $yhOrderNo = null;
    // 商户的应用的唯一订单号
    private $appOrderNo = null;

    /**
     * 统一下单接口
     * @param Request $request
     */
    public function index(Request $request){
        $this->parameterChecking($request);
    }
    
    /**
     * 过滤所有参数
     * @param Request $request
     * @return array
     */
    private function parameterChecking(Request $request) : array{
        
    }
    
    /**
     * 生成yh唯一的订单号, 写入数据库
     * @return string
     */
    private function createOrderNo() : string{
        
    }
    
    /**
     * 获取上游的唯一订单号
     */
    private function getOrderNo(): string{
        
    }
    
    /**
     * 通道选择
     */
    private function choosePassageway(){
        
    }
    
    /**
     * 商户的应用在yh存储的对应通道的对接资料
     */
    private function passagewayInfo(){
        
    }
}
