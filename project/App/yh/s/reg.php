<?php
namespace App\yh\s;

use App\yh\m;

class reg {
    public function checkUser(\Request $requset){
        $username = $requset->post('username');
        $passwd = $requset->post('passwd');
        
        $table = m\mainUser::getTable();
        $arr = obj(m\mainUser::class)->getAll();
        var_dump($table);
        var_dump($arr);
    }
}