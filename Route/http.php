<?php

return [
    Route::get('/',function(){
        return \Response::setContentType('html')->returnData('hello world');
    }),
    Route::get('/data/upload/{yearmonth}/{day}/{name}', function($yearmonth, $day, $name) {
        $filename = ROOT . "data/upload/$yearmonth/$day/$name";
        if (file_exists($filename)) return \Response::setContentType(substr(strrchr($name, '.'), 1))->returnData(file_get_contents($filename));
        else return \Response::setStatus(404)->exitData();
    }),
    // yh
    Route::group(['middleware'=>['web'], 'namespace'=> 'App\yh\c' ], function(){
        // 接口开发调试页面
        Route::get('/dev','Dev\Dev@index');
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
        // 检测 merchant web登入令牌
        Route::group(['middleware'=>['merchant']],function(){
            // 令牌以旧换新( 重置有效期 )
            Route::post('/user/token','user\Login@changeToken');
            // 商户资料
            Route::restful('/merchant','merchant\Info');
            // 应用资料
            Route::restful('/application','merchant\Application');
            // 商户公私钥资料
            Route::restful('/secret','merchant\Secret');
          
        });
        // 不检测 token
        Route::group(['prefix'=>'/admin','namespace' => 'admin'],function(){
            // 管理员登入
            Route::post('/login','Login@index');
        });
        // 检测 管理员登入令牌
        Route::group(['middleware' => ['admin'],'prefix'=>'/admin'], function() {
            // 令牌以旧换新( 重置有效期 )
            Route::post('/token', 'admin\Login@changeToken');
            // 管理员新增管理员
            Route::post('/reg', 'admin\Reg@index');
            // 管理员设置自己密码
            Route::put('/setpasswd', 'admin\Reg@setPasswd');
            // 管理员 管理商户信息
            Route::restful('/merchant', 'admin\Merchant');
            // 管理员 管理用户 登入/支付, 启用/禁用
            Route::restful('/user', 'admin\User');
            // 管理员 管理通道信息
            Route::restful('/passageway', 'admin\Passageway');
        });

                // 检测 api调用令牌
        Route::group(['middleware'=>['payment'],'prefix'=>'/api'],function(){
            Route::post('/create', 'UnifiedOrderRequest@index');
        });
           
    }),
    
    Route::group(['middleware'=>['web','api'], 'namespace'=> 'App\Dev' ], function(){
        // 数据库测试
        Route::get('/mysql','mysql\Contr\index2Contr@indexDo');
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
        
        // 二维码
        Route::get('/qrcode','QRcode\index@index');
        // 验证码
        Route::get('/yzm','Yzm\index@index');

        // 新共能开发
        Route::get('/new',['uses' => 'development\Contr\indexContr@indexDo','middleware'=>['api']]);

        Route::any('/route1',['as' => 'tt1', 'uses' => 'development\Contr\indexContr@indexDo']);
    }),
            
    // 管道模式
    Route::get('/p',['middleware'=>['web','testMiddleware'],'namespace'=> 'App\Dev', 'uses'=>'Pipeline\index@index']),
    
    '/test' => ['as' => 'tt1', 'uses' => function(){
        redirect('/dev', ['id'=>'1232323123'],'test' );
        return 'test';
    }],
    '/404' => ['middleware'=>['web'],'uses'=>function(){
//            return Response::setStatus(404)->view(404);
        return false;
    }],
    
    // 支持隐式路由
    Route::any('/{app}/{contr}/{action}', function ($app, $contr, $action) {
        return run('\App/'.$app.'/Contr/'.$contr.'Contr', $action);
    }),
    
    '/hello' => function(\Main\Core\Request $request, Main\Core\Response $response){
        $response->setStatus(400);
        return $request->get('name');
    }
];