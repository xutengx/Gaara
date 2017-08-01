<?php
return [
        // 支持隐式路由
    '/'.IN_SYS => function(){
        \Main\Core\RouteImplicit::Start();
    },
            
            
    '/index.php/test/user/{id?}' => 'App\index\Contr\IndexContr@indexDo',
    '/mysql' => 'App\mysql\Contr\indexContr@indexDo',
    '/power.html' => 'App\jurisdiction\Contr\IndexContr@index',
    '/enable.html' => ['as' => '别名dis', 'uses'=> 'App\jurisdiction\Contr\enable@index'],
    '/disable.html' => 'App\jurisdiction\Contr\disable@index'

];