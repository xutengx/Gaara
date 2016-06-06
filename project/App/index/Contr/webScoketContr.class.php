<?php
namespace App\index\Contr;
use \Main\Core\Controller\Module;
defined('IN_SYS')||exit('ACC Denied');
class webScoketContr{
    /**
     * 设置Session
     */
    public function __construct(){
        CLI||exit('error!');
    }
    public function indexDo(){
        $work = obj('\Main\Core\Workerman',false,'websocket://0.0.0.0:2345');
        $work->count = 1;
        $work->onConnect = function($connec){
            // http验证阶段
            $connec->onWebSocketConnect = function($conn , $http_header){
                $session = obj('session')->getSession();
                $conn->id = $session['id'];
//                obj('session')->commit($session);
            };
        };
        $work->onMessage = function($conn, $data){
            $data = json_decode($data, true);

            $data['state'] = 1;
            $data['form_id'] = $conn->id;
            $data['time'] = date('Y-m-d H:i:s', time());
            $messageid = obj('messageModel')->insertData($data);
            foreach($conn->worker->connections as $con){
                if($con->id == $data['to_id'] ){
                    obj('messageModel')->checkState($messageid, 2);
                    $con->send($data['content']);
                }
            }
        };
        $work->runAll();
    }
}