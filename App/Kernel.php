<?php

namespace App;

use Main\Core\Kernel as HttpKernel;

class Kernel extends HttpKernel {
    // 全局中间件
    protected $middlewareGlobel = [
        // 根据http协议返回
        \Main\Core\Middleware\ReturnResponse::class,
        // post请求体大小检测
        \Main\Core\Middleware\ValidatePostSize::class,
    ];
    // 路由中间件
    protected $middlewareGroups = [
        'web' => [
            // 开启session
            \Main\Core\Middleware\StartSession::class,
        ],
        'api' => [
            // 允许跨域
            \Main\Core\Middleware\CrossDomainAccess::class,
            // 访问频率控制  30次 / 60s
            \Main\Core\Middleware\ThrottleRequests::class.'@30@60',
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
        'login' =>[
            \App\yh\Middleware\SignCheck::class
        ],
        'payment' =>[
            // 登入令牌
            \App\yh\Middleware\SignCheck::class,
            // 支付权限
            \App\yh\Middleware\PaymentCheck::class
        ]
    ];
    
    // 数据库异常处理
    protected $pdo = [
        // pdo错误码
        1146 => \App\yh\Exception\createTable::class,
    ];
    
}
