<?php

namespace App;

use Main\Core\Kernel as HttpKernel;

class Kernel extends HttpKernel {
    // 全局中间件
    protected $middlewareGlobel = [
        // post请求体大小检测
        \Main\Core\Middleware\ValidatePostSize::class,
    ];
    // 路由中间件
    protected $middlewareGroups = [
        'web' => [
            // 开启session
            \Main\Core\Middleware\StartSession::class,
        ],
        'jurisdiction' => [
            \App\Middleware\Jurisdiction::class,
        ],
        'api' => [
            // 访问频率控制  30次 / 60s
            \Main\Core\Middleware\ThrottleRequests::class.'@30@60',
        ],
        'sendMail' => [
            // 访问频率控制  1次 / 30s
            \Main\Core\Middleware\ThrottleRequests::class.'@1@30',
        ],
    ];
    
    // 数据库异常处理
    protected $pdo = [
        // pdo错误码
        1146 => \App\yh\Exception\createTable::class,
    ];
    
}
