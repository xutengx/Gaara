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
        $work->users = [];
        $work->onConnect = function($connec){
            // http验证阶段
            $connec->onWebSocketConnect = function($conn , $http_header){
                $session = obj('session')->getSession();
                $conn->id = $session['id'];
                $conn->account = $session['account'];
                // 当前进程所有连接对象
                $tmp['id'] = $conn->id;
                $tmp['account'] = $conn->account;
                $conn->worker->users[] = $tmp;
//                foreach($conn->worker->connections as $con){
//                    $tmp['id'] = $con->id;
//                    $tmp['account'] = $con->account;
//                    $users[] = $tmp;
//                }
                foreach($conn->worker->connections as $con){
                    $con->send(json_encode(['login', $conn->worker->users]));
                }
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
                    $con->send(json_encode(['message', $data]));
                    break;
                }
            }
        };

        $work->onClose = function($connection){
            foreach($connection->worker->users as $k => $v){
                if($v['id'] == $connection->id){
                    unset($connection->worker->users[$k]);
                    break;
                }

            }
            foreach($connection->worker->connections as $con){
                $con->send(json_encode(['login', $connection->worker->users]));
            }
        };

        $work->runAll();
    }
}