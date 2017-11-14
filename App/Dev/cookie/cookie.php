<?php

namespace App\Dev\cookie;

use Gaara\Core\Request;
use \Gaara\Core\Controller;

class cookie extends Controller {

    public function index(Request $request){
//        $request->cookie['test'] = 'test';
//        $request->setcookie('te1','1',350);
        return ($request->cookie);
        
    }
}
