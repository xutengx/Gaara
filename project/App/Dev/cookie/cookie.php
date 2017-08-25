<?php

namespace App\Dev\cookie;

use Main\Core\Request;
use \Main\Core\Controller\HttpController;

class cookie extends HttpController {

    public function index(Request $request){
//        $request->cookie['test'] = 'test';
//        $request->setcookie('te1','1',350);
        var_dump($request->cookie);exit;
        
    }
}
