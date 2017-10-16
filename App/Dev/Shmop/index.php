<?php

namespace App\Dev\Shmop;
/**
 * 共享内存
 */
class index{  
    public function index() {
        $key = 0x4337b700;
        $size = 4096;
        $shmid = @shmop_open($key, 'c', 0644, $size);
        if ($shmid === FALSE) {
            exit('shmop_open error!');
        }
        $data = serialize(['世界，你好！我将写入很多的数据，你能罩得住么？','pp' => 1, 'rr' => '2']);

        $length = shmop_write($shmid, pack('a*', $data), 0);
//        $length = shmop_write($shmid, pack('a*', $data), 0);
        if ($length === FALSE) {
            exit('shmop_write error!');
        }

        @shmop_close($shmid);

        exit('succ');
    }
    
    public function read(){
        $key = 0x4337b700;
        $size = 4096;
        $shmid = @shmop_open($key, 'c', 0644, $size);

        $size = shmop_size($shmid);
        $data = shmop_read($shmid, 0, $size);
        var_dump(unserialize($data));
        exit;
    }
}