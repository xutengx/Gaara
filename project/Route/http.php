<?php
return [
        // 支持隐式路由
    '/'.IN_SYS => function(){
        \Main\Core\RouteImplicit::Start();
    },
            
            
    '/test/{id?}' => [
        'domain' => '{username}.gitxt.com',
        'as' => '别名dis',
        'uses'=> function($id){
            
            var_dump($id);
        
            var_dump($_SERVER);exit;
        }
    ],
    '/index.php/test/user/{id?}' => 'App\index\Contr\IndexContr@indexDo',
    '/mysql' => 'App\mysql\Contr\indexContr@indexDo',
    '/power.html' => 'App\jurisdiction\Contr\IndexContr@index',
    '/enable.html' => [
        'domain' => '{username}.gitxt.com',
        'as' => '别名dis',
        'uses'=> 'App\jurisdiction\Contr\enable@index'
    ],
    '/disable.html' => 'App\jurisdiction\Contr\disable@index'

];