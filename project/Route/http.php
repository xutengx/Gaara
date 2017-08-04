<?php
//namespace Main\Core;

return [
//            
//    0 => [
//        '/test' => [
//            'domain' => '{username}.gitxt.com',
//            'as' => '别名dis',
//            'uses'=> function($username, $id, $w){
//
//                var_dump($username);
//                var_dump($id);
//                var_dump($w);
//
//                var_dump($_SERVER);exit;
//            }
//        ],
//    ],  
//    Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
//        return 'byebye';
//    }]),
//    '/index.php/test/user/{id?}' => 'App\index\Contr\IndexContr@indexDo',
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
//        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

//        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            
//                var_dump($r);
//                var_dump($a);
//                var_dump($b);
//                var_dump($c);
            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
//    sleep(1);
//    '/test/{id?}/ww/{ww?}' => [
//        'domain' => '{username}.gitxt.com',
//        'as' => '别名dis',
//        'uses'=> function($username, $id, $w){
//            
//            var_dump($username);
//            var_dump($id);
//            var_dump($w);
//        
//            var_dump($_SERVER);exit;
//        }
//    ],
//    '/index.php/test/user/{id?}' => 'App\index\Contr\IndexContr@indexDo',
//    '/mysql' => 'App\mysql\Contr\indexContr@indexDo',
//    '/power.html' => 'App\jurisdiction\Contr\IndexContr@index',
//    '/enable.html' => [
//        'domain' => '{username}.gitxt.com',
//        'as' => '别名dis',
//        'uses'=> 'App\jurisdiction\Contr\enable@index'
//    ],
//    '/disable.html' => 'App\jurisdiction\Contr\disable@index',
//            
//    [
//        'domain' => '{admin}.gitxt.com',
//        'rule' => '/testgroup',
//        'as' => '/testgroup',
//        'rule' => '/testgroup',
//        
//    ],     
//    '/testgroup' => [
//        '/user' => 'App\jurisdiction\Contr\enable@index',
//        '/user/{c}' => [
//            'as' => '分组路由as别名',
//            'domain' => '{admin}.gitxt.com',
//            'uses'  => function ($a , $b){
//                var_dump($a);
//                var_dump($b);
//                var_dump('路由分组 is ok !');
//            }
//        ],
//    ],  
//    '/testgroup' => [
//        '/user' => 'App\jurisdiction\Contr\enable@index',
//        '/user/{c}' => [
//            'as' => '分组路由as别名',
//            'domain' => '{admin}.gitxt.com',
//            'uses'  => function ($a , $b){
//                var_dump($a);
//                var_dump($b);
//                var_dump('路由分组 is ok !');
//            }
//        ],
//    ]
            
    // 支持隐式路由(兼容式) 
    '/'.IN_SYS => function(){
        \Main\Core\RouteImplicit::Start();
    },
    // 支持隐式路由
    Route::any('/{app}/{contr}/{action}', function ($app, $contr, $action) {
        return obj('\App/'.$app.'/Contr/'.$contr.'Contr')->$action(obj('Request'));
    })
];