<?php
return [
//    '/mysql/' => 'App\mysql\Contr\indexContr@indexDo',
//    '/{this_is_a}/{this_is_b}' => 'App\index\Contr\IndexContr@indexDo',
//    '/function/{亲亲我轻轻}/{参数2}' => function($request1, $request2){
//    
//        var_dump(obj('f')->get);
//    
//        var_dump($request1);
//        var_dump($request2);
//        exit;
//        echo 'this is a function !';
//        return obj('App\index\Contr\IndexContr')->indexDo($request1, $request2);
//    },
//    '/' => function($request1){
//        
//        var_dump($request1);
//        exit;
//        exit('this host');
//    },
            
//    'dev' => 'App\development\Contr\indexContr@tt',
//    'user/login/{userid}/{artcle_id}' => 'App\development\Contr\testContr@route',
//    'user/login/{userid}/{artcle_id}' => 'App\development\Contr\testContr@route',
//    'user/login/{userid}/{artcle_id}' => 'App\development\Contr\testContr@route',
            
    '/index.php/test/user/{id?}' => 'App\index\Contr\IndexContr@indexDo',
    '/mysql' => 'App\mysql\Contr\indexContr@indexDo',
];