<?php

declare(strict_types = 1);
namespace App;

use Main\Core\Kernel as HttpKernel;
use Main\Core\Middleware\ReturnResponse;
use Main\Core\Middleware\ValidatePostSize;
use Main\Core\Middleware\StartSession;
use Main\Core\Middleware\VerifyCsrfToken;
use Main\Core\Middleware\CrossDomainAccess;
use Main\Core\Middleware\ThrottleRequests;
use Main\Core\Middleware\PerformanceMonitoring;
use Main\Core\Middleware\ExceptionHandler;

class Kernel extends HttpKernel {
    // 全局中间件
    protected $middlewareGlobel = [
        // 异常处理
//        ExceptionHandler::class,
        // 移除意外输出,根据http协议返回
        ReturnResponse::class,
        // post请求体大小检测
        ValidatePostSize::class,
        // php-console 显示执行性能
        PerformanceMonitoring::class,
    ];
    // 路由中间件
    protected $middlewareGroups = [
        'web' => [
            // 开启session
            StartSession::class,
            // csrf校验
            VerifyCsrfToken::class,
        ],
        'api' => [
            // 允许跨域
            CrossDomainAccess::class,
            // 访问频率控制  30次 / 60s
            ThrottleRequests::class.'@30@60',
        ],
//        'sendMail' => [
//            // 访问频率控制  1次 / 30s
//            \Main\Core\Middleware\ThrottleRequests::class.'@1@30',
//        ],
//        'testMiddleware' =>[
//            \Main\Core\Middleware\ThrottleRequests::class.'@30@60',
//            \App\Middleware\test1::class,
//            \App\Middleware\test2::class,
//        ],
        'admin' =>[
            // 管理员登入令牌
            \App\yh\Middleware\AdminCheck::class
        ],
        'merchant' =>[
            // 商户登入令牌
            \App\yh\Middleware\SignCheck::class
        ],
        'payment' =>[
            // 商户支付api调用令牌
            \App\yh\Middleware\PaymentCheck::class
        ]
    ];
    
    
}
