<?php

namespace App\Dev\cookie;

use Gaara\Core\Request;
use \Gaara\Core\Controller\HttpController;

class cookie extends HttpController {

    public function index(Request $request){
//        $request->cookie['test'] = 'test';
//        $request->setcookie('te1','1',350);
        return ($request->cookie);
        
    }
}
