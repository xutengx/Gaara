<?php
namespace App\development\Dev;
defined('IN_SYS') || exit('ACC Denied');

class asyncDev {
    
    public static function  testStatic(){
        echo ' <br> static function ! <br>';
    }
    
    public function  test(){
        echo ' <br> a function ! <br>';
    }
    
}
