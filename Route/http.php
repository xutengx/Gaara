<?php

return [
    // yh
    Route::group(['middleware'=>['api'], 'namespace'=> 'App\yh\c' ], function(){
        // 不检测 token
        Route::group(['prefix'=>'/user','namespace' => 'user'],function(){
            // 邮箱检测
            Route::get('/email','Reg@email');
            // 注册( 邮件发送 )
            Route::post('/reg',['uses'=>'Reg@index']);
            // 注册 设置密码
            Route::post('/setpasswd','Reg@setPasswd');
            // 登入
            Route::post('/login','Login@index');
            // 忘记密码( 邮件发送 )
            Route::post('/forget','ForgetPasswd@index');
            // 忘记密码 设置密码
            Route::post('/resetpasswd','ForgetPasswd@setPasswd');
        });
        // 检测 token
        Route::group(['middleware'=>['login']],function(){
            // 令牌以旧换新( 重置有效期 )
            Route::post('/user/token','user\Login@changeToken');

            // 商户资料
            Route::restful('/merchant','merchant\Info');
          
        });
        
        // 支付api
        Route::group(['middleware'=>['payment']],function(){
                
        });
           
    }),
    
    Route::group(['middleware'=>['web','api'], 'namespace'=> 'App\Dev' ], function(){
        // 数据库测试
        Route::get('/mysql','mysql\Contr\indexContr@indexDo');
        // 数据库测试
        Route::get('/mysql/test','mysql\Contr\indexContr@test');
        // 邮件测试 给 emailAddr 发一份邮件
        Route::get('/mail/{emailAddr}',['middleware'=>['sendMail'], 'uses'=>'mail\index@send']);
        // 视图相关
        Route::get('/view','view\index@index');
        Route::any('/view/ajax','view\index@getAjax');
        
        // cookie
        Route::get('/cookie','cookie\cookie@index');
        Route::get('/cookie/cookie/cookie','cookie\cookie@index');
        

        // 新共能开发
        Route::get('/new',['uses' => 'development\Contr\indexContr@indexDo','middleware'=>['api']]);

        Route::any('/route1',['as' => 'tt1', 'uses' => 'development\Contr\indexContr@indexDo']);
    }),
            
    // 管道模式
    Route::get('/p',['middleware'=>['web','testMiddleware'],'namespace'=> 'App\Dev', 'uses'=>'Pipeline\index@index']),
    
    '/test' => ['as' => 'tt1', 'uses' => function(){
            var_dump( obj(Conf::class)->db   );
    }],
    
    // 支持隐式路由
    Route::any('/{app}/{contr}/{action}', function ($app, $contr, $action) {
        return run('\App/'.$app.'/Contr/'.$contr.'Contr', $action);
    })
];