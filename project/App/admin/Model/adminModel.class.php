<?php
namespace App\admin\Model;
class adminModel extends \Main\Core\Model{
    // 登入
    public function login($username, $passwd){
        return $this->where([
            'username'=>':username',
            'passwd'=>':passwd',
            'status'=>'1',
        ])->getRow([
            ':username'=>$username,
            ':passwd'=>$passwd
        ]);
    }
    
    // 登入后的状态修改
    public function change_last_login_time_and_ip($id){
        $time = $_SERVER['REQUEST_TIME'];
        $ip   = isset($_SERVER['HTTP_X_REAL_IP'])?$_SERVER['HTTP_X_REAL_IP']:$_SERVER['REMOTE_ADDR'] ;
        $this->data([
            'last_login_time'   => ':last_login_time',
            'last_login_ip'     => ':last_login_ip'
        ])->where([
            'id' => $id
        ])->update([
            ':last_login_time'  => $time,
            ':last_login_ip'    => $ip
        ]);
        return [
            $time, $ip
        ];
    }
    
    // 登录状态检测 adminObj 调用
    public function check_login_status($id) {
        return $this
            ->where([
                'id' => ':id'
            ])->getRow([
        ':id' => $id
        ]);
    }
}
