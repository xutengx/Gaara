<?php

return [
    Route::group(['middleware'=>['web'], 'namespace'=> 'App\Dev' ], function(){
        // 数据库测试
        Route::get('/mysql','mysql\Contr\indexContr@indexDo');
        // 邮件测试 给 emailAddr 发一份邮件
        Route::get('/mail/{emailAddr}','mail\index@send');
        // 视图相关
        Route::get('/view','view\index@index');
        Route::put('/view/ajax','view\index@getAjax');
        // 
        // 新共能开发
        Route::get('/new',['uses' => 'development\Contr\indexContr@indexDo','middleware'=>['api']]);

        Route::any('/route1',['as' => 'tt1', 'uses' => 'development\Contr\indexContr@indexDo']);
    }),
    '/test' => ['as' => 'tt1', 'uses' => function(){
            return url('qwe111');
    }],
    
    // 支持隐式路由
    Route::any('/{app}/{contr}/{action}', function ($app, $contr, $action) {
        return run('\App/'.$app.'/Contr/'.$contr.'Contr', $action);
    })
];