<?php
return [
        // 支持隐式路由
    '/'.IN_SYS => function(){
        \Main\Core\RouteImplicit::Start();
    },
            
            
    '/test/{id?}/ww/{ww?}' => [
        'domain' => '{username}.gitxt.com',
        'as' => '别名dis',
        'uses'=> function($username, $id, $w){
            
            var_dump($username);
            var_dump($id);
            var_dump($w);
        
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
    '/disable.html' => 'App\jurisdiction\Contr\disable@index',
            
    [
        'domain' => '{admin}.gitxt.com',
        'rule' => '/testgroup',
        'as' => '/testgroup',
        
        'rule' => '/testgroup',
        
    ],     
    '/testgroup' => [
        '/user' => 'App\jurisdiction\Contr\enable@index',
        '/user/{c}' => [
            'as' => '分组路由as别名',
            'domain' => '{admin}.gitxt.com',
            'uses'  => function ($a , $b){
                var_dump($a);
                var_dump($b);
                var_dump('路由分组 is ok !');
            }
        ],
    ],  
    '/testgroup' => [
        '/user' => 'App\jurisdiction\Contr\enable@index',
        '/user/{c}' => [
            'as' => '分组路由as别名',
            'domain' => '{admin}.gitxt.com',
            'uses'  => function ($a , $b){
                var_dump($a);
                var_dump($b);
                var_dump('路由分组 is ok !');
            }
        ],
    ]
];