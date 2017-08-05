<?php

return [
    // 数据库测试
    Route::get('/mysql','App\mysql\Contr\indexContr@indexDo'),
    // 新共能开发
    Route::get('/new','App\development\Contr\indexContr@indexDo'),
    
    Route::any('/route1',['as' => 'tt1', 'uses' => 'App\development\Contr\indexContr@indexDo']),   
    
    // 支持隐式路由(兼容式) 
    '/'.IN_SYS => function(){
        \Main\Core\RouteImplicit::Start();
    },
    // 支持隐式路由
    Route::any('/{app}/{contr}/{action}', function ($app, $contr, $action) {
        return obj('\App/'.$app.'/Contr/'.$contr.'Contr')->$action(obj('Request'));
    })
];