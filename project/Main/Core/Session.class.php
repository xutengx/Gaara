<?php
namespace Main\Core;
class Session{
    final public function __construct(){
        $this->start_session();
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