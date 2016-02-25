<?php
namespace Main;
class session extends Base{
    private static $ins = null;
    private function __construct(){
        $this->start_session();
    }
    public static function getins(){
        if((self::$ins instanceof self) || (self::$ins = new self())) return self::$ins;
    }
    public function start_session(){
        if(!is_dir(SESSIONPATH)) $this->base_mkdir(SESSIONPATH);
        session_save_path(SESSIONPATH);
        session_set_cookie_params(SESSIONLIFE);
        session_cache_expire(SESSIONLIFE);
        session_start();
        return true;
    }
}