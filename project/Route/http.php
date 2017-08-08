<?php

return [
    Route::group(['middleware'=>['web'], 'namespace'=> 'App' ], function(){
        // 数据库测试
        Route::get('/mysql','mysql\Contr\indexContr@indexDo');
        // 新共能开发
        Route::get('/new/{test?}',['uses' => 'development\Contr\indexContr@indexDo','domain'=> '{user}.{domain}.com','middleware'=>['api']]);

        Route::any('/route1',['as' => 'tt1', 'uses' => 'development\Contr\indexContr@indexDo']);
    }),
    // 多语言 
    Route::group(['prefix'=>'/{language}','middleware'=>['web'], 'namespace'=> 'App' ], function(){
        Route::get('/lang',function(Request $request, $language){
            switch (strtolower($language)) {
                case 'cn':
                    $string = '中文';
                    break;
                default:
                    $string = 'English';
                    break;
            }
            return $string;
        });
    }),
    
    // 支持隐式路由(兼容式) 
    '/'.IN_SYS => function(){
        \Main\Core\RouteImplicit::Start();
    },
    // 支持隐式路由
    Route::any('/{app}/{contr}/{action}', function ($app, $contr, $action) {
        return obj('\App/'.$app.'/Contr/'.$contr.'Contr')->$action(obj('Request'));
    })
];