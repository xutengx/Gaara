<?php

declare(strict_types = 1);
namespace App\yh\Middleware;
defined('IN_SYS') || exit('ACC Denied');

use Main\Core\Middleware;
use \Main\Core\Request;

/**
 * 规则支付api调用权限
 */
class PaymentCheck extends Middleware {

    public function handle(Request $request) {
        $userInfo = $request->userinfo;
        if($userInfo['payment'] !== 1)
            return $this->error('没有调用支付api的权限');
    }

}
