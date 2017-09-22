#!/usr/bin/env php
<?php
class Server
{
    private $serv;
/**
server {
        listen       80;
        server_name  swoole.gaara.com;
    	root /mnt/hgfs/www/git/php_/public/;
    	index index.php;

    	# 错误相应页
        error_page   500 502 503 504  /50x.html;
        location = /50x.html {
            root   html;
        }

        location / {
            proxy_http_version 1.1;
            proxy_set_header Connection "keep-alive";
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header http_host $server_name;
            proxy_set_header request_scheme $scheme;
            proxy_set_header request_uri $request_uri;
            if (!-e $request_filename) {
                 proxy_pass http://127.0.0.1:9501;
            }
        }
    }
 */
    public function __construct() {
        $http = new swoole_http_server("127.0.0.1", 9501);
        $http->on('request', function ($request, $response) {
            ob_start();
//            var_dump($request);
            $_SERVER['path_info'] = $request->server['path_info'];
            $_SERVER['HTTP_HOST'] = $request->header['http_host'];
            $_SERVER['REQUEST_SCHEME'] = $request->header['request_scheme'];
            $_SERVER['QUERY_STRING'] = $request->server['query_string'];
            $_SERVER['REQUEST_URI'] = $request->header['request_uri'];
            $_SERVER['HTTP_ACCEPT'] = $request->header['accept'];
            $_SERVER['REQUEST_METHOD'] = $request->server['request_method'];
            $_POST = $request->post ?? [];
            $_GET = $request->get ?? [];
            $_FILES = $request->files ?? [];
            $_COOKIE = $request->cookie ?? [];
//            var_dump($_SERVER);
            require './init.php';
            $response->end(\Response::getData());
        });
        $http->start();
        
//        $this->serv = new swoole_server("0.0.0.0", 9501);
//        $this->serv->set(array(
//            'worker_num' => 8,
//            'daemonize' => false,
//        ));
//
//        $this->serv->on('Start', array($this, 'onStart'));
//        $this->serv->on('Connect', array($this, 'onConnect'));
//        $this->serv->on('Receive', array($this, 'onReceive'));
//        $this->serv->on('Close', array($this, 'onClose'));
//
//        $this->serv->start();
    }

    public function onStart( $serv ) {
        echo "Start\n";
    }

    public function onConnect( $serv, $fd, $from_id ) {
        $serv->send( $fd, "Hello {$fd}!" );
    }

    public function onReceive( swoole_server $serv, $fd, $from_id, $data ) {
        echo "Get Message From Client {$fd}:{$data}\n";
        $serv->send($fd, $data);
    }

    public function onClose( $serv, $fd, $from_id ) {
        echo "Client {$fd} close connection\n";
    }
}
// 启动服务器 Start the server
$server = new Server();