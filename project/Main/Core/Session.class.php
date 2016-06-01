<?php
namespace Main\Core;
use Piwik\Exception\Exception;

class Session{
    private static $status = false;
    final public function __construct(){
//        throw new \Exception();
        $this->session_start();
    }
    public function session_start(){
        if(self::$status === false){
            if(!is_dir(SESSIONPATH)) obj('tool')->__mkdir(SESSIONPATH);
            session_save_path(SESSIONPATH);
            session_set_cookie_params(SESSIONLIFE);
            session_cache_expire(SESSIONLIFE);
            session_start();
            self::$status = true;
        }
        return true;
    }
    public function session_commit(){
        self::$status === true && session_commit();
        return true;
    }
    public function session_write_close(){
        return $this->session_commit();
    }
}