<?php

namespace App\Dev\Yzm;
/**
 * 2维码
 */
class index{  
    public function index(\Image $Image){
        echo '<img src="'.$Image->Yzm(150, 50, 8).'" />';
        exit;
    }
}