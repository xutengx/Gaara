<?php

namespace App;

use Main\Core\Kernel as HttpKernel;

class Kernel extends HttpKernel {
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middlewareGlobel = [
        // post请求体大小检测
        \Main\Core\Middleware\ValidatePostSize::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // 开启session
            \Main\Core\Middleware\StartSession::class,
//            \App\Http\Middleware\EncryptCookies::class,
//            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
//            \Illuminate\Session\Middleware\StartSession::class,
//            \Illuminate\Session\Middleware\AuthenticateSession::class,
//            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
//            \Main\Core\Middleware\VerifyCsrfToken::class,
//            \Illuminate\Routing\Middleware\SubstituteBindings::class,
//            \App\Http\Middleware\Language::class, // Alex Globel Language Settings 2017-06-20 copy
        ],
        'jurisdiction' => [
            \App\Middleware\Jurisdiction::class,
        ],
        'api' => [
            // 访问频率控制  100次 / 60s
            \Main\Core\Middleware\ThrottleRequests::class.'@100@60',
        ],
    ];

}
