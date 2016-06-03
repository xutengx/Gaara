<?php
namespace App\index\Contr;
use \Main\Core\Controller\Module;
defined('IN_SYS')||exit('ACC Denied');
class webScoketContr{
    /**
     * 设置Session
     */
    public function __construct(){
        php_sapi_name() === 'cli'||exit('error!');
    }
    public function indexDo(){
        $work = obj('\Main\Core\Workerman',false,'websocket://0.0.0.0:2345');
        $work->count = 1;
        $work->onConnect = function($connec){
            $connec->onWebSocketConnect = function($conn , $http_header){
//                if($user = obj('userObj')->init(obj('userModel'))){
//
//                    $conn->id = $user->id;
//                    var_dump($http_header);
//                }
//                else $conn->close();
                var_dump(get_session());
//                var_dump($_SESSION);
//                session_write_close();
            };
        };
        $work->onMessage = function($conn, $data){
            $data = json_decode($data, true);
            foreach($conn->worker->connections as $con){
//                var_dump($conn->id);
//                var_dump($con->id);
//                var_dump($con->$data['to_id']);
                if($con->id == $data['to_id'] )
                    $con->send($data['content']);
            }
        };
        $work->runAll();
    }
}