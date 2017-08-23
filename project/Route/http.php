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
            // 设置密码
            Route::post('/setpasswd','Reg@setPasswd');
            // 登入
            Route::post('/login','Login@index');
        });
        // 检测 token
        Route::group(['prefix'=>'/user','middleware'=>['login'],'namespace' => 'user'],function(){
            // 令牌以旧换新( 重置有效期 )
            Route::post('/token','Login@changeToken');
            // 用户资料
            Route::restful('/user/info','Info');
            
        });
    }),
    
    Route::group(['middleware'=>['web','api'], 'namespace'=> 'App\Dev' ], function(){
        // 数据库测试
        Route::get('/mysql','mysql\Contr\indexContr@indexDo');
        // 邮件测试 给 emailAddr 发一份邮件
        Route::get('/mail/{emailAddr}',['middleware'=>['sendMail'], 'uses'=>'mail\index@send']);
        // 视图相关
        Route::get('/view','view\index@index');
        Route::put('/view/ajax','view\index@getAjax');
        
        // 新共能开发
        Route::get('/new',['uses' => 'development\Contr\indexContr@indexDo','middleware'=>['api']]);

        Route::any('/route1',['as' => 'tt1', 'uses' => 'development\Contr\indexContr@indexDo']);
    }),
            
    // 管道模式
    Route::get('/p',['middleware'=>['web','testMiddleware'],'namespace'=> 'App\Dev', 'uses'=>'Pipeline\index@index']),
    
    '/test' => ['as' => 'tt1', 'uses' => function(){
            return 'test';
    }],
    
    // 支持隐式路由
    Route::any('/{app}/{contr}/{action}', function ($app, $contr, $action) {
        return run('\App/'.$app.'/Contr/'.$contr.'Contr', $action);
    })
];