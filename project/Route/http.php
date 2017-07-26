<?php
return [
        // 支持隐式路由
    '/'.IN_SYS => function(){
        \Main\Core\RouteImplicit::Start();
    },
            
            
    '/index.php/test/user/{id?}' => 'App\index\Contr\IndexContr@indexDo',
    '/mysql' => 'App\mysql\Contr\indexContr@indexDo',
    

];